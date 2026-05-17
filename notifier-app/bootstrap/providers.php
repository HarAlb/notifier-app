<?php

use App\Providers\AppServiceProvider;

return [
    AppServiceProvider::class,
    \Src\Infrastructure\Providers\NotificationBatchProvider::class,
    \Src\Infrastructure\Providers\TransactionServiceProvider::class,
    \Src\Infrastructure\Providers\DispatcherServiceProvider::class,
    \Src\Infrastructure\Providers\QueuePublisherProvider::class,
    \Src\Infrastructure\Providers\MailSenderProvider::class,
];
