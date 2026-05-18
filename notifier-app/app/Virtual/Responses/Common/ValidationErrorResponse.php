<?php

declare(strict_types=1);

namespace App\Virtual\Responses\Common;

use OpenApi\Attributes as OAT;

#[OAT\Schema(
    title: 'ValidationErrorResponse',
    description: 'Validation error response'
)]
final class ValidationErrorResponse
{
    #[OAT\Property(
        property: 'message',
        type: 'string',
        example: 'The channel field is required.'
    )]
    public string $message;

    #[OAT\Property(
        property: 'errors',
        type: 'object',
        example: [
            'channel' => [
                'The channel field is required.',
            ],
        ],
        additionalProperties: new OAT\AdditionalProperties(
            type: 'array',
            items: new OAT\Items(type: 'string')
        )
    )]
    public object $errors;
}
