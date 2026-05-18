<?php

declare(strict_types=1);

namespace Src\Infrastructure\Providers;

use App\Providers\AppServiceProvider;
use Src\Application\NotificationBatch\AsyncDispatcherInterface;
use Src\Infrastructure\Async\LaravelAsyncDispatcher;

class DispatcherServiceProvider extends AppServiceProvider
{
    #[\Override]
    public function register(): void
    {
        $this->app->singleton(AsyncDispatcherInterface::class, LaravelAsyncDispatcher::class);
    }
}
