<?php

declare(strict_types=1);

namespace Src\Infrastructure\Providers;

use App\Providers\AppServiceProvider;
use Src\Application\Shared\Contracts\MailSenderInterface;
use Src\Infrastructure\Mail\LaravelMailSender;

class MailSenderProvider extends AppServiceProvider
{
    #[\Override]
    public function register(): void
    {
        $this->app->singleton(MailSenderInterface::class, LaravelMailSender::class);
    }
}
