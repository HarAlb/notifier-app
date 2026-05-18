<?php

declare(strict_types=1);

namespace Src\Application\NotificationBatch\SendEmail;

use Ramsey\Uuid\UuidInterface;

final class SendEmailCommand
{
    public function __construct(
        public UuidInterface $messageId,
        public int $tries = 5,
    ) {}
}
