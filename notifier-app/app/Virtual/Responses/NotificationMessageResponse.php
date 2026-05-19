<?php

declare(strict_types=1);

namespace App\Virtual\Responses;

use OpenApi\Attributes as OAT;

#[OAT\Schema(
    title: 'NotificationMessageResponse',
    description: 'Notification message resource'
)]
final class NotificationMessageResponse
{
    #[OAT\Property(
        property: 'id',
        type: 'string',
        format: 'uuid',
        example: '313741c7-8dc9-42c9-99b5-d6fa4bf35d92'
    )]
    public string $id;

    #[OAT\Property(
        property: 'status',
        type: 'string',
        example: 'pending'
    )]
    public string $status;

    #[OAT\Property(
        property: 'attempts',
        type: 'integer',
        example: 1
    )]
    public int $attempts;

    #[OAT\Property(
        property: 'last_error',
        type: 'string',
        example: null,
        nullable: true
    )]
    public ?string $last_error = null;

    #[OAT\Property(
        property: 'created_at',
        type: 'string',
        format: 'date-time',
        example: '2026-05-18 19:21:54'
    )]
    public string $created_at;

    #[OAT\Property(
        property: 'updated_at',
        type: 'string',
        format: 'date-time',
        example: '2026-05-18 19:21:54'
    )]
    public string $updated_at;


    #[
        OAT\Property(
            title: 'History',
            items: new OAT\Items(ref: '#/components/schemas/NotificationMessageHistoryResponse')
        )
    ]
    public array $history;
}
