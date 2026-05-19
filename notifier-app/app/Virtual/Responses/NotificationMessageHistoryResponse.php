<?php

declare(strict_types=1);

namespace App\Virtual\Responses;

use OpenApi\Attributes as OAT;

#[OAT\Schema(
    schema: 'NotificationMessageHistoryResponse',
    type: 'object'
)]
final class NotificationMessageHistoryResponse
{
    #[OAT\Property(
        property: 'id',
        type: 'string',
        format: 'uuid',
        example: 'cdb9e499-94bf-44ff-889c-9aec4c0bc8ff'
    )]
    public string $id;

    #[OAT\Property(
        property: 'status',
        type: 'string',
        example: 'processing'
    )]
    public string $status;

    #[OAT\Property(
        property: 'error',
        type: 'string',
        nullable: true,
        example: null
    )]
    public ?string $error;

    #[OAT\Property(
        property: 'created_at',
        type: 'string',
        format: 'date-time',
        example: '2026-05-19 09:56:15'
    )]
    public string $created_at;
}
