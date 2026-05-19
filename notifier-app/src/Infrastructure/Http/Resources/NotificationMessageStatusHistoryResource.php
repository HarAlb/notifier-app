<?php

declare(strict_types=1);

namespace Src\Infrastructure\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\Domain\NotificationBatch\Entities\NotificationMessage;
use Src\Domain\NotificationBatch\Entities\NotificationMessageStatusHistory;

final class NotificationMessageStatusHistoryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var NotificationMessageStatusHistory $history */
        $history = $this->resource;

        return [
            'id' => $history->getId(),
            'status' => $history->getStatus()->value,
            'error' => $history->getError(),
            'created_at' => $history->getCreatedAt()->format('Y-m-d H:i:s'),
        ];
    }
}
