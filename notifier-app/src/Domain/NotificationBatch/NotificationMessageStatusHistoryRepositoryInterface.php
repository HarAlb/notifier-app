<?php

declare(strict_types=1);

namespace Src\Domain\NotificationBatch;

use Ramsey\Uuid\UuidInterface;
use Src\Domain\NotificationBatch\Entities\NotificationMessageStatusHistory;

interface NotificationMessageStatusHistoryRepositoryInterface
{
    public function append(NotificationMessageStatusHistory $history): void;

    public function findByMessageIds(array $messageIds): array;
}
