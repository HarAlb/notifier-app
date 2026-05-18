<?php

declare(strict_types=1);

namespace App\Virtual\Request;

use OpenApi\Attributes as OAT;

#[OAT\Schema(
    title: 'NotificationBatchStoreRequest',
    required: ['channel', 'body', 'recipient_ids']
)]
final class NotificationBatchStoreRequest
{
    #[OAT\Property(
        property: 'channel',
        type: 'string',
        example: 'email',
        enum: ['email', 'sms', 'push']
    )]
    public string $channel;

    #[OAT\Property(
        property: 'subject',
        type: 'string',
        example: 'TEST AB',
        nullable: true,
        maxLength: 255
    )]
    public ?string $subject = null;

    #[OAT\Property(
        property: 'body',
        type: 'string',
        example: 'HELLO MESSAGE'
    )]
    public string $body;

    #[OAT\Property(
        property: 'priority',
        type: 'string',
        nullable: true,
        enum: ['transactional', 'critical', 'marketing']
    )]
    public ?string $priority = null;

    #[OAT\Property(
        property: 'recipient_ids',
        type: 'array',
        items: new OAT\Items(
            type: 'integer',
            example: 1
        ),
        example: [1, 2, 3]
    )]
    public array $recipient_ids;
}
