<?php

declare(strict_types=1);

namespace Src\Infrastructure\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\Domain\NotificationBatch\Entities\NotificationBatch;

final class NotificationBatchResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var NotificationBatch $notificationBatch */
        $notificationBatch = $this->resource;

        return [
            'id' => $notificationBatch->getId(),
            'idempotency_key' => $notificationBatch->getIdempotencyKey()->value(),
            'channel' => $notificationBatch->getChannel()->value(),
            'subject' => $notificationBatch->getSubject()->value(),
            'status' => $notificationBatch->getStatus()->value(),
            'body' => $notificationBatch->getBody()->value(),
            'created_at' => $notificationBatch->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $notificationBatch->getUpdatedAt()->format('Y-m-d H:i:s'),
            'messages_count' => $notificationBatch->getMessages()->count(),
        ];
    }
}
