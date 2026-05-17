<?php

declare(strict_types=1);

namespace Src\Infrastructure\Mail;

use Illuminate\Support\Facades\Mail;
use Src\Application\Shared\Contracts\MailSenderInterface;

final class LaravelMailSender implements MailSenderInterface
{
    public function send(string $to, string $subject, string $body): void
    {
        Mail::raw($body, function ($message) use ($to, $subject) {
            $message->to($to)
                ->subject($subject);
        });
    }
}
