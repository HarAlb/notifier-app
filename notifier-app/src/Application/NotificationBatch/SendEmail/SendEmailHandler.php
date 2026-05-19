<?php

declare(strict_types=1);

namespace Src\Application\NotificationBatch\SendEmail;

use Src\Application\NotificationBatch\MessageStateTransitionLogger;
use Src\Application\Shared\Contracts\MailSenderInterface;
use Src\Domain\NotificationBatch\NotificationBatchRepositoryInterface;
use Src\Domain\NotificationBatch\NotificationMessageRepositoryInterface;
use Src\Domain\NotificationBatch\ValueObjects\MessageStatus;

final readonly class SendEmailHandler
{
    public function __construct(
        private NotificationMessageRepositoryInterface $messageRepository,
        private NotificationBatchRepositoryInterface $batchRepository,
        private MailSenderInterface $mailSender,
        private MessageStateTransitionLogger $logger
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

        $this->logger->log(
            $message->getId(),
            MessageStatus::PROCESSING,
            null
        );

        try {
            $batch = $this->batchRepository->findById($message->getBatchId());
            $email = 'Test Error'; //fake()->email();

            $this->mailSender->send(
                to: $email,
                subject: $batch->getSubject()->value(),
                body: $batch->getBody()->value()
            );

            $message->markAsSent();
            $this->messageRepository->save($message);

            $this->logger->log(
                $message->getId(),
                MessageStatus::SENT,
                null
            );

            $this->batchRepository->tryMarkAsCompleted($batch->getId());
        } catch (\Throwable $e) {
            if ($message->getAttempts() + 1 >= $command->tries) {
                $message->markAsFailed(
                    $e->getMessage()
                );

                $this->logger->log(
                    $message->getId(),
                    MessageStatus::FAILED,
                    $message->getLastError()
                );

                $this->messageRepository->save($message);

                return;
            }

            $this->messageRepository->markAsRetry(
                $message->getId(),
                $e->getMessage()
            );

            $this->logger->log(
                $message->getId(),
                MessageStatus::PENDING,
                $message->getLastError()
            );

            throw $e;
        }
    }
}
