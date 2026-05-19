<?php

declare(strict_types=1);

namespace Src\Infrastructure\Providers;

use Illuminate\Support\ServiceProvider;
use Src\Domain\NotificationBatch\NotificationBatchRepositoryInterface;
use Src\Domain\NotificationBatch\NotificationMessageRepositoryInterface;
use Src\Domain\NotificationBatch\NotificationMessageStatusHistoryRepositoryInterface;
use Src\Infrastructure\Persistence\QueryNotificationBatchRepository;
use Src\Infrastructure\Persistence\QueryNotificationMessageRepository;
use Src\Infrastructure\Persistence\QueryNotificationMessageStatusHistoryRepository;

final class NotificationBatchProvider extends ServiceProvider
{
    #[\Override]
    public function register(): void
    {
        $this->app->singleton(NotificationBatchRepositoryInterface::class, QueryNotificationBatchRepository::class);
        $this->app->singleton(NotificationMessageRepositoryInterface::class, QueryNotificationMessageRepository::class);
        $this->app->singleton(NotificationMessageStatusHistoryRepositoryInterface::class, QueryNotificationMessageStatusHistoryRepository::class);
    }
}
