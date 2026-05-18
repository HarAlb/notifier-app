<?php

declare(strict_types=1);

namespace Src\Infrastructure\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Src\Domain\NotificationBatch\Entities\NotificationMessage;

final class NotificationMessageResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        /** @var NotificationMessage $notificationMessage */
        $notificationMessage = $this->resource;

        return [
            'id' => $notificationMessage->getId(),
            'status' => $notificationMessage->getStatus()->value,
            'attempts' => $notificationMessage->getAttempts(),
            'last_error' => $notificationMessage->getLastError(),
            'created_at' => $notificationMessage->getCreatedAt()->format('Y-m-d H:i:s'),
            'updated_at' => $notificationMessage->getUpdatedAt()->format('Y-m-d H:i:s'),
        ];
    }
}
