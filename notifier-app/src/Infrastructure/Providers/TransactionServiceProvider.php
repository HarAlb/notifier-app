<?php

namespace Src\Infrastructure\Providers;

use App\Providers\AppServiceProvider;
use Src\Application\Shared\Contracts\TransactionServiceInterface;
use Src\Infrastructure\Persistence\TransactionService;

class TransactionServiceProvider extends AppServiceProvider
{
    #[\Override]
    public function register(): void
    {
        $this->app->singleton(TransactionServiceInterface::class, TransactionService::class);
    }
}
