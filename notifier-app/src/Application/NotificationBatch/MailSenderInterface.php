<?php

declare(strict_types=1);

namespace Src\Application\NotificationBatch;

interface MailSenderInterface
{
    public function send(string $to, string $subject, string $body): void;
}
