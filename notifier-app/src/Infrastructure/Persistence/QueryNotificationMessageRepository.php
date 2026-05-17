<?php

declare(strict_types=1);

namespace Src\Infrastructure\Persistence;

use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Src\Domain\NotificationBatch\Entities\NotificationMessage;
use Src\Domain\NotificationBatch\NotificationMessageRepositoryInterface;
use Src\Domain\NotificationBatch\ValueObjects\MessageStatus;

final class QueryNotificationMessageRepository implements NotificationMessageRepositoryInterface
{
    private const TABLE = 'notification_messages';

    public function save(NotificationMessage $message): void
    {
        DB::table(self::TABLE)->updateOrInsert(
            ['id' => $message->getId()->toString()],
            [
                'batch_id' => $message->getBatchId()->toString(),
                'recipient_id' => $message->getRecipientId(),
                'status' => $message->getStatus()->value,
                'attempts' => $message->getAttempts(),
                'last_error' => $message->getLastError(),
                'created_at' => $message->getCreatedAt()->format('Y-m-d H:i:s'),
                'updated_at' => $message->getUpdatedAt()->format('Y-m-d H:i:s'),
            ]
        );
    }

    public function saveMany(array $messages): void
    {
        if (empty($messages)) {
            return;
        }

        $data = array_map(function (NotificationMessage $msg) {
            return [
                'id' => $msg->getId()->toString(),
                'batch_id' => $msg->getBatchId()->toString(),
                'recipient_id' => $msg->getRecipientId(),
                'status' => $msg->getStatus()->value,
                'attempts' => $msg->getAttempts(),
                'last_error' => $msg->getLastError(),
                'created_at' => $msg->getCreatedAt()->format('Y-m-d H:i:s'),
                'updated_at' => $msg->getUpdatedAt()->format('Y-m-d H:i:s'),
            ];
        }, $messages);

        DB::table(self::TABLE)->insert($data);
    }

    public function findById(UuidInterface $id): ?NotificationMessage
    {
        $row = DB::table(self::TABLE)
            ->where('id', $id->toString())
            ->first();

        if (! $row) {
            return null;
        }

        return $this->mapToDomain($row);
    }

    public function claimForProcessing(UuidInterface $id): bool
    {
        $updated = DB::table(self::TABLE)
            ->where('id', $id)
            ->where('status', MessageStatus::PENDING->value)
            ->update([
                'status' => MessageStatus::PROCESSING->value,
                'updated_at' => now(),
            ]);

        return $updated === 1;
    }

    public function findPendingByBatchId(UuidInterface $batchId): array
    {
        $rows = DB::table(self::TABLE)
            ->where('batch_id', $batchId->toString())
            ->where('status', MessageStatus::PENDING->value)
            ->get();

        return $rows->map(fn ($row) => $this->mapToDomain($row))->all();
    }

    private function mapToDomain(object $row): NotificationMessage
    {
        return NotificationMessage::fromDatabase(
            Uuid::fromString($row->id),
            Uuid::fromString($row->batch_id),
            (int) $row->recipient_id,
            MessageStatus::tryFrom($row->status),
            (int) $row->attempts,
            $row->last_error,
            new \DateTimeImmutable($row->created_at),
            new \DateTimeImmutable($row->updated_at)
        );
    }
}
