<?php

declare(strict_types=1);

namespace Src\Infrastructure\Persistence;

use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Src\Domain\NotificationBatch\Entities\NotificationBatch;
use Src\Domain\NotificationBatch\NotificationBatchRepositoryInterface;
use Src\Domain\NotificationBatch\ValueObjects\Body;
use Src\Domain\NotificationBatch\ValueObjects\Channel;
use Src\Domain\NotificationBatch\ValueObjects\IdempotencyKey;
use Src\Domain\NotificationBatch\ValueObjects\Priority;
use Src\Domain\NotificationBatch\ValueObjects\Status;
use Src\Domain\NotificationBatch\ValueObjects\Subject;

class QueryNotificationBatchRepository implements NotificationBatchRepositoryInterface
{
    public function save(NotificationBatch $batch): void
    {
        DB::table('notification_batches')->updateOrInsert(
            ['id' => $batch->getId()->toString()],
            [
                'id' => $batch->getId()->toString(),
                'idempotency_key' => $batch->getIdempotencyKey()->value(),
                'status' => $batch->getStatus()->value(),
                'channel' => $batch->getChannel()->value(),
                'subject' => $batch->getSubject()->value(),
                'body' => $batch->getBody()->value(),
                'priority' => $batch->getPriority()->value,
                'created_at' => $batch->getCreatedAt(),
                'updated_at' => $batch->getUpdatedAt(),
            ]
        );
    }

    public function findById(UuidInterface $id): ?NotificationBatch
    {
        $row = DB::table('notification_batches')
            ->where('id', $id->toString())
            ->first();

        if ($row === null) {
            return null;
        }

        return $this->mapToDomain($row);
    }

    public function findByIdempotencyKey(IdempotencyKey $idempotencyKey): ?NotificationBatch
    {
        $row = DB::table('notification_batches')
            ->where('idempotency_key', $idempotencyKey->value())
            ->first();

        if ($row === null) {
            return null;
        }

        return $this->mapToDomain($row);
    }

    private function mapToDomain(object $row): NotificationBatch
    {
        return NotificationBatch::restore(
            id: Uuid::fromString($row->id),
            idempotencyKey: new IdempotencyKey($row->idempotency_key),
            channel: new Channel($row->channel),
            body: new Body($row->body),
            priority: Priority::tryFrom($row->priority),
            status: Status::fromString($row->status),
            createdAt: new \DateTimeImmutable($row->created_at),
            updatedAt: new \DateTimeImmutable($row->updated_at),
            subject: new Subject($row->subject),
        );
    }
}
