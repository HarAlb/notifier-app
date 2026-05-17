<?php

declare(strict_types=1);

namespace Src\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Domain\NotificationBatch\NotificationBatchRepositoryInterface;
use Src\Infrastructure\Persistence\QueryNotificationBatchRepository;

final class NotificationBatchProvider extends ServiceProvider
{
    #[\Override]
    public function register(): void
    {
        $this->app->singleton(NotificationBatchRepositoryInterface::class, QueryNotificationBatchRepository::class);
    }
}
