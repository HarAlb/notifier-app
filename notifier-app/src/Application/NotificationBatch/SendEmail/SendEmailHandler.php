<?php

declare(strict_types=1);

namespace Src\Application\NotificationBatch\SendEmail;

final readonly class SendEmailHandler
{
    public function __construct(
        private NotificationMessageRepositoryInterface $messageRepository,
        private MailSenderInterface $mailSender,
        private TransactionServiceInterface $transaction,
    ) {}

    public function handle(SendEmailCommand $command, int $attemptNumber = 1): void
    {
        // 1. Атомарный захват сообщения (только если статус pending)
        $message = $this->messageRepository->findAndLockForProcessing(
            MessageId::fromString($command->messageId)
        );

        if (!$message || !$message->canBeProcessed()) {
            return; // уже обработано или не найдено
        }

        // 2. Меняем статус на processing в той же транзакции
        $this->transaction->run(function () use ($message) {
            $message->markAsProcessing();
            $this->messageRepository->save($message);
        });

        try {
            // 3. Получаем email пользователя и данные уведомления
            //    (здесь можно достать batch через репозиторий или предварительно загрузить)
            $batch = $this->batchRepository->findById($message->getBatchId());
            $userEmail = $this->userRepository->getEmailById($message->getRecipientId());

            // 4. Отправка email через адаптер
            $this->mailSender->send(
                to: $userEmail,
                subject: $batch->getSubject() ?? 'Notification',
                body: $batch->getBody()->value()
            );

            // 5. Успех – обновляем статус
            $this->transaction->run(function () use ($message) {
                $message->markAsSent();
                $this->messageRepository->save($message);
            });
        } catch (\Throwable $e) {
            // 6. Ошибка – обновляем счётчик попыток
            $this->transaction->run(function () use ($message, $e) {
                $message->markAsFailed($e->getMessage());
                $this->messageRepository->save($message);
            });

            // 7. Если не превышен лимит – бросаем исключение для ретрая
            if ($attemptNumber < 5) {
                throw $e;
            }

            // 8. Иначе – отправляем в Dead Letter (опционально)
            $this->deadLetterStore->store($message, $e);
        }
    }
}
