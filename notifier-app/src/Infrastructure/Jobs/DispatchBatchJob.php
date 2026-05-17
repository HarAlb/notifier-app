<?php

namespace Src\Infrastructure\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\UuidInterface;
use Src\Application\NotificationBatch\DispatchBatch\DispatchBatchCommand;
use Src\Application\NotificationBatch\DispatchBatch\DispatchBatchHandler;

class DispatchBatchJob implements ShouldQueue
{
    use Dispatchable, Queueable, InteractsWithQueue, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct(private readonly UuidInterface $batchId)
    {
        Log::info('asdasda');
    }

    /**
     * Execute the job.
     */
    public function handle(DispatchBatchHandler $batchHandler): void
    {
        $batchHandler->handle(new DispatchBatchCommand($this->batchId));
    }
}
