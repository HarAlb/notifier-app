<?php

declare(strict_types=1);

namespace Src\Infrastructure\Async;

use Ramsey\Uuid\UuidInterface;
use Src\Application\NotificationBatch\AsyncDispatcherInterface;
use Src\Infrastructure\Jobs\DispatchBatchJob;

final class LaravelAsyncDispatcher implements AsyncDispatcherInterface
{
    public function dispatchBatch(UuidInterface $batchId): void
    {
        DispatchBatchJob::dispatch($batchId);
    }
}
