<?php

declare(strict_types=1);

namespace Src\Application\NotificationBatch;

use Ramsey\Uuid\UuidInterface;

interface AsyncDispatcherInterface
{
    public function dispatchBatch(UuidInterface $batchId): void;
}
