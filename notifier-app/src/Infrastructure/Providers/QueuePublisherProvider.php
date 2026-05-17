<?php

declare(strict_types=1);

namespace Src\Infrastructure\Providers;

use App\Providers\AppServiceProvider;
use Src\Application\NotificationBatch\QueuePublisherInterface;
use Src\Infrastructure\Messaging\RabbitMqQueuePublisher;

final class QueuePublisherProvider extends AppServiceProvider
{
    public function register(): void {
        $this->app->singleton(QueuePublisherInterface::class, RabbitMqQueuePublisher::class);
    }
}
