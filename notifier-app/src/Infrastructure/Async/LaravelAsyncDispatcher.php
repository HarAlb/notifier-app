<?php

declare(strict_types=1);

namespace Src\Infrastructure\Async;

use Ramsey\Uuid\UuidInterface;
use Src\Application\NotificationBatch\AsyncDispatcherInterface;
use Src\Domain\NotificationBatch\ValueObjects\Priority;
use Src\Infrastructure\Jobs\DispatchBatchJob;

final class LaravelAsyncDispatcher implements AsyncDispatcherInterface
{
    public function dispatchBatch(UuidInterface $batchId, Priority $priority): void
    {
        DispatchBatchJob::dispatch($batchId)
            ->onConnection('rabbitmq')
            ->onQueue($this->resolveQueueName($priority));
    }

    private function resolveQueueName(Priority $priority): string
    {
        return match ($priority) {
            Priority::TRANSACTIONAL => 'notifications.transactional',
            Priority::CRITICAL => 'notifications.critical',
            Priority::MARKETING => 'notifications.marketing',
        };
    }
}
