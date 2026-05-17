<?php

declare(strict_types=1);

namespace Src\Domain\NotificationBatch;

use Ramsey\Uuid\UuidInterface;
use Src\Domain\NotificationBatch\Entities\NotificationBatch;
use Src\Domain\NotificationBatch\ValueObjects\IdempotencyKey;

interface NotificationBatchRepositoryInterface
{
    public function save(NotificationBatch $batch): void;

    public function findById(UuidInterface $id): ?NotificationBatch;

    public function findByIdempotencyKey(IdempotencyKey $idempotencyKey): ?NotificationBatch;
}
