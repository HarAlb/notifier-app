<?php

declare(strict_types=1);

namespace Src\Application\NotificationBatch;

interface QueuePublisherInterface
{
    /**
     * @param object $payload Любой DTO/команда, которую потом получит SendEmailJob
     */
    public function publish(string $queue, object $payload, int $priority): void;
}
