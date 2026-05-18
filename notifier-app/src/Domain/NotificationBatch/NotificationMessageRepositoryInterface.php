<?php

declare(strict_types=1);

namespace Src\Domain\NotificationBatch;

use Ramsey\Collection\Collection;
use Ramsey\Uuid\UuidInterface;
use Src\Domain\NotificationBatch\Entities\NotificationMessage;

interface NotificationMessageRepositoryInterface
{
    public function save(NotificationMessage $message): void;

    public function findById(UuidInterface $id): ?NotificationMessage;

    public function saveMany(array $messages): void;

    /**
     * @return NotificationMessage[]
     */
    public function findPendingByBatchId(UuidInterface $batchId): array;

    public function findByBatchId(UuidInterface $batchId): array;

    public function claimForProcessing(UuidInterface $id): bool;

    public function markAsRetry(UuidInterface $id, string $error): void;
}
