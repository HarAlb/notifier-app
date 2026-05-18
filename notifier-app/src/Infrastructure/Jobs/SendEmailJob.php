<?php

declare(strict_types=1);

namespace Src\Infrastructure\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;
use Ramsey\Uuid\UuidInterface;
use Src\Application\NotificationBatch\SendEmail\SendEmailCommand;
use Src\Application\NotificationBatch\SendEmail\SendEmailHandler;

final class SendEmailJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 5;

    public array $backoff = [5, 15, 60, 300];

    public function __construct(
        private UuidInterface $messageId
    ) {}

    public function handle(SendEmailHandler $handler): void
    {
        $command = new SendEmailCommand($this->messageId, $this->tries);
        $handler->handle($command);
    }
}
