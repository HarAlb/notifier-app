<?php

declare(strict_types=1);

namespace App\Virtual\Responses\Wrappers;

use OpenApi\Attributes as OAT;

#[OAT\Schema(title: 'Notification Message Response Wrapper')]
class NotificationMessageResponseWrapper
{
    #[
        OAT\Property(
            title: 'Data',
            items: new OAT\Items(ref: '#/components/schemas/NotificationMessageResponse')
        )
    ]
    public array $data;
}
