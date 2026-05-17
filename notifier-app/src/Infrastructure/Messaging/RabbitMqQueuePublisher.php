<?php

declare(strict_types=1);

namespace Src\Infrastructure\Messaging;

use Src\Application\NotificationBatch\QueuePublisherInterface;
use Src\Application\NotificationBatch\SendEmail\SendEmailCommand;
use Src\Infrastructure\Jobs\SendEmailJob;

class RabbitMqQueuePublisher implements QueuePublisherInterface
{
    public function publish(string $queue, object $payload): void
    {
        match (true) {
            $payload instanceof SendEmailCommand => $this->dispatchEmail($payload, $queue),
            default => throw new \InvalidArgumentException('Unsupported payload type'),
        };
    }

    private function dispatchEmail(SendEmailCommand $command, string $queue): void
    {
        SendEmailJob::dispatch($command->messageId)
            ->onQueue($queue)
            ->onConnection('rabbitmq');
    }
}
