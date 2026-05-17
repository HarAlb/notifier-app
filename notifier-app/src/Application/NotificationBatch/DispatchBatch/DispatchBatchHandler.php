<?php

declare(strict_types=1);

namespace Src\Application\NotificationBatch\DispatchBatch;

use Illuminate\Support\Facades\Log;
use Src\Application\NotificationBatch\QueuePublisherInterface;
use Src\Application\NotificationBatch\SendEmail\SendEmailCommand;
use Src\Application\Shared\Contracts\TransactionServiceInterface;
use Src\Domain\NotificationBatch\Entities\NotificationBatch;
use Src\Domain\NotificationBatch\NotificationBatchRepositoryInterface;
use Src\Domain\NotificationBatch\ValueObjects\Priority;
use Src\Domain\NotificationBatch\ValueObjects\Status;

final class DispatchBatchHandler
{
    public function __construct(
        private readonly NotificationBatchRepositoryInterface $batchRepository,
        private readonly QueuePublisherInterface              $publisher,
        private readonly TransactionServiceInterface          $transaction,
    )
    {
    }

    public function handle(DispatchBatchCommand $command): void
    {
        $notificationBatch = $this->batchRepository->findById($command->batchId);

        if (!$notificationBatch || $notificationBatch->getStatus()->equals(Status::pending())) {
            return;
        }

        $this->transaction->run(function () use ($notificationBatch): void {
            $notificationBatch->markAsDispatched();
            $this->batchRepository->save($notificationBatch);
        });

        Log::info('WORKED');
//
//        $queueName = $this->resolveQueueName($notificationBatch->getPriority());
//        $priority = $this->resolvePriorityLevel($notificationBatch->getPriority());
//
//        // 3. Публикуем каждое сообщение в очередь
//        foreach ($messages as $message) {
//            $this->publisher->publish($queueName, $payload, $priority);
//        }
    }

    private function resolveQueueName(Priority $priority): string
    {
        return match ($priority) {
            Priority::TRANSACTIONAL => 'notifications.transactional',
            Priority::CRITICAL => 'notifications.critical',
            Priority::MARKETING => 'notifications.marketing',
        };
    }

    private function createPayload(NotificationBatch $notificationBatch): object
    {
        return match ($notificationBatch->getChannel()->value()) {
            'email' => new SendEmailCommand($notificationBatch->getId()),
            'sms'   => new SendSmsCommand($notificationBatch->getId()),
        };
    }
}
