<?php

use App\Providers\AppServiceProvider;
use Src\Infrastructure\Providers\DispatcherServiceProvider;
use Src\Infrastructure\Providers\MailSenderProvider;
use Src\Infrastructure\Providers\NotificationBatchProvider;
use Src\Infrastructure\Providers\QueuePublisherProvider;
use Src\Infrastructure\Providers\TransactionServiceProvider;

return [
    AppServiceProvider::class,
    NotificationBatchProvider::class,
    TransactionServiceProvider::class,
    DispatcherServiceProvider::class,
    QueuePublisherProvider::class,
    MailSenderProvider::class,
];
