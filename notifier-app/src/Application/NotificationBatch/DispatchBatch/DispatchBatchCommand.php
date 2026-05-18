<?php

declare(strict_types=1);

namespace Src\Application\NotificationBatch\DispatchBatch;

use Ramsey\Uuid\UuidInterface;

final readonly class DispatchBatchCommand
{
    public function __construct(
        public UuidInterface $batchId
    ) {}
}
