<?php

declare(strict_types=1);

namespace Src\Application\Shared\Contracts;

interface MailSenderInterface
{
    public function send(string $to, string $subject, string $body): void;
}
