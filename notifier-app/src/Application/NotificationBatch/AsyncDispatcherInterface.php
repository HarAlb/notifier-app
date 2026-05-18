<?php

declare(strict_types=1);

namespace Src\Application\NotificationBatch;

use Ramsey\Uuid\UuidInterface;
use Src\Domain\NotificationBatch\ValueObjects\Priority;

interface AsyncDispatcherInterface
{
    public function dispatchBatch(UuidInterface $batchId, Priority $priority): void;
}
