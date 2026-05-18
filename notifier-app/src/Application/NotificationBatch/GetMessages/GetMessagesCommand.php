<?php

declare(strict_types=1);

namespace Src\Application\NotificationBatch\GetMessages;

use Ramsey\Uuid\UuidInterface;

final class GetMessagesCommand {
    public function __construct(public UuidInterface $batchId) {

    }
}
