<?php

declare(strict_types=1);

namespace Src\Infrastructure\Messaging;

use Src\Application\NotificationBatch\QueuePublisherInterface;

class RabbitMqQueuePublisher implements QueuePublisherInterface
{
    public function publish(string $queue, object $payload, int $priority): void
    {
        match (true) {
            $payload instanceof SendEmailCommand => $this->dispatchEmail($payload, $queue, $priority),
            $payload instanceof SendSmsCommand   => $this->dispatchSms($payload, $queue, $priority),
            default => throw new \InvalidArgumentException('Unsupported payload type'),
        };
    }

    private function dispatchEmail(SendEmailCommand $command, string $queue, int $priority): void
    {
        SendEmailJob::dispatch($command->messageId)
            ->onQueue($queue)
            ->onConnection('rabbitmq')
            ->setPriority($priority);
    }

    private function dispatchSms(SendSmsCommand $command, string $queue, int $priority): void
    {
        SendSmsJob::dispatch($command->messageId)
            ->onQueue($queue)
            ->onConnection('rabbitmq')
            ->setPriority($priority);
    }
}
