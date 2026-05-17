<?php

declare(strict_types=1);

namespace Src\Application\NotificationBatch\SendEmail;

use Src\Application\Shared\Contracts\MailSenderInterface;
use Src\Domain\NotificationBatch\NotificationBatchRepositoryInterface;
use Src\Domain\NotificationBatch\NotificationMessageRepositoryInterface;

final readonly class SendEmailHandler
{
    public function __construct(
        private NotificationMessageRepositoryInterface $messageRepository,
        private NotificationBatchRepositoryInterface $batchRepository,
        private MailSenderInterface $mailSender
    ) {}

    public function handle(SendEmailCommand $command): void
    {
        $claimed = $this->messageRepository->claimForProcessing($command->messageId);

        if (! $claimed) {
            return;
        }

        $message = $this->messageRepository->findById($command->messageId);

        if ($message->isProcessed()) {
            return;
        }

        try {
            $batch = $this->batchRepository->findById($message->getBatchId());
            $email = fake()->email();

            $this->mailSender->send(
                to: $email,
                subject: $batch->getSubject()->value() ?? 'Notification',
                body: $batch->getBody()->value()
            );

            $message->markAsSent();
            $this->messageRepository->save($message);

            $this->batchRepository->tryMarkAsCompleted($batch->getId());
        } catch (\Throwable $e) {

            $message->markAsFailed($e->getMessage());
            $this->messageRepository->save($message);
        }
    }
}
