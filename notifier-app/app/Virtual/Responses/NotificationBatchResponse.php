<?php

declare(strict_types=1);

namespace App\Virtual\Responses;
use OpenApi\Attributes as OAT;

#[OAT\Schema(
    title: 'NotificationBatchResponse',
    description: 'Notification batch response'
)]
final class NotificationBatchResponse
{
    #[OAT\Property(
        property: 'id',
        type: 'string',
        format: 'uuid',
        example: '313741c7-8dc9-42c9-99b5-d6fa4bf35d92'
    )]
    public string $id;

    #[OAT\Property(
        property: 'idempotency_key',
        type: 'string',
        format: 'uuid',
        example: '019e35c8-dc6e-746e-9a06-d47749b70459'
    )]
    public string $idempotency_key;

    #[OAT\Property(
        property: 'channel',
        type: 'string',
        example: 'email'
    )]
    public string $channel;

    #[OAT\Property(
        property: 'subject',
        type: 'string',
        example: 'Subject if email',
        nullable: true
    )]
    public ?string $subject;

    #[OAT\Property(
        property: 'body',
        type: 'string',
        example: 'Body'
    )]
    public string $body;


    #[OAT\Property(
        property: 'status',
        type: 'string',
        example: 'pending'
    )]
    public string $status;

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

    #[OAT\Property(
        property: 'messages_count',
        type: 'integer',
        example: 1
    )]
    public int $messages_count;
}
