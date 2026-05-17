<?php

declare(strict_types=1);

namespace Src\Application\NotificationBatch\DispatchBatch;

use Src\Application\NotificationBatch\QueuePublisherInterface;
use Src\Application\NotificationBatch\SendEmail\SendEmailCommand;
use Src\Domain\NotificationBatch\Entities\NotificationBatch;
use Src\Domain\NotificationBatch\Entities\NotificationMessage;
use Src\Domain\NotificationBatch\NotificationBatchRepositoryInterface;
use Src\Domain\NotificationBatch\NotificationMessageRepositoryInterface;
use Src\Domain\NotificationBatch\ValueObjects\Priority;

final readonly class DispatchBatchHandler
{
    public function __construct(
        private NotificationBatchRepositoryInterface $batchRepository,
        private NotificationMessageRepositoryInterface $messagesRepository,
        private QueuePublisherInterface $publisher
    ) {}

    public function handle(DispatchBatchCommand $command): void
    {
        $claimed = $this->batchRepository->claimAsDispatched($command->batchId);

        if (! $claimed) {
            return;
        }

        $notificationBatch = $this->batchRepository->findById($command->batchId);

        $queueName = $this->resolveQueueName($notificationBatch->getPriority());

        $messages = $this->messagesRepository->findPendingByBatchId($notificationBatch->getId());

        foreach ($messages as $message) {
            $payload = $this->createPayload($notificationBatch, $message);

            $this->publisher->publish($queueName, $payload);
        }
    }

    private function resolveQueueName(Priority $priority): string
    {
        return match ($priority) {
            Priority::TRANSACTIONAL => 'notifications.transactional',
            Priority::CRITICAL => 'notifications.critical',
            Priority::MARKETING => 'notifications.marketing',
        };
    }

    private function createPayload(NotificationBatch $notificationBatch, NotificationMessage $message): object
    {
        return match ($notificationBatch->getChannel()->value()) {
            'email' => new SendEmailCommand($message->getId())
        };
    }
}
