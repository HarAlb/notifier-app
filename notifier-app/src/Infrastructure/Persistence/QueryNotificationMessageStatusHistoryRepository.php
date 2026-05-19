<?php

declare(strict_types=1);

namespace Src\Infrastructure\Persistence;

use Illuminate\Support\Facades\DB;
use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Src\Domain\NotificationBatch\Entities\NotificationMessage;
use Src\Domain\NotificationBatch\Entities\NotificationMessageStatusHistory;
use Src\Domain\NotificationBatch\NotificationMessageStatusHistoryRepositoryInterface;
use Src\Domain\NotificationBatch\ValueObjects\MessageStatus;

final class QueryNotificationMessageStatusHistoryRepository implements NotificationMessageStatusHistoryRepositoryInterface
{

    private const TABLE = 'notification_message_status_history';

    public function append(NotificationMessageStatusHistory $history): void
    {
        DB::table(self::TABLE)->insert([
            'id' => $history->getId()->toString(),
            'message_id' => $history->getMessageId()->toString(),
            'status' => $history->getStatus(),
            'error' => $history->getError(),
            'created_at' => $history->getCreatedAt()->format('Y-m-d H:i:s'),
        ]);
    }

    public function findByMessageIds(array $messageIds): array
    {
        $rows = DB::table(self::TABLE)
            ->whereIn('message_id', array_map(
                fn($id) => $id->toString(),
                $messageIds
            ))
            ->orderBy('created_at')
            ->get();

        $grouped = [];

        foreach ($rows as $row) {
            $grouped[$row->message_id][] = $this->mapToDomain($row);
        }

        return $grouped;
    }

    private function mapToDomain(object $row): NotificationMessageStatusHistory
    {
        return NotificationMessageStatusHistory::create(
            Uuid::fromString($row->id),
            Uuid::fromString($row->message_id),
            MessageStatus::tryFrom($row->status),
            $row->error,
            new \DateTimeImmutable($row->created_at)
        );
    }
}
