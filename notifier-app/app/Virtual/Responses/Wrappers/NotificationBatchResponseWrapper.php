<?php

declare(strict_types=1);

namespace App\Virtual\Responses\Wrappers;

use App\Virtual\Responses\NotificationBatchResponse;
use OpenApi\Attributes as OAT;

#[OAT\Schema(title: 'Notification Batch Response Wrapper')]
class NotificationBatchResponseWrapper
{
    #[OAT\Property(
        title: 'data',
    )]
    public NotificationBatchResponse $data;
}
