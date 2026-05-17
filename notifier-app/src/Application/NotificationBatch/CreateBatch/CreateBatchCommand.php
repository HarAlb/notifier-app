<?php

declare(strict_types=1);

namespace Src\Application\NotificationBatch\CreateBatch;

use Ramsey\Uuid\UuidInterface;

final class CreateBatchCommand
{
    public function __construct(
        public UuidInterface $id,
        public string $idempotencyKey,
        public string $channel,
        public ?string $subject,
        public string $body,
        public string $priority,
        public array $recipientIds,
    ) {}
}
