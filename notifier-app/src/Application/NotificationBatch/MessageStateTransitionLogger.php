<?php

declare(strict_types=1);

namespace Src\Application\NotificationBatch;

use Ramsey\Uuid\Uuid;
use Ramsey\Uuid\UuidInterface;
use Src\Domain\NotificationBatch\Entities\NotificationMessageStatusHistory;
use Src\Domain\NotificationBatch\NotificationMessageStatusHistoryRepositoryInterface;
use Src\Domain\NotificationBatch\ValueObjects\MessageStatus;

final readonly class MessageStateTransitionLogger
{
    public function __construct(
        private NotificationMessageStatusHistoryRepositoryInterface $repo
    ) {}

    public function log(
        UuidInterface $messageId,
        MessageStatus $status,
        ?string $error
    ): void {
        $this->repo->append(
            NotificationMessageStatusHistory::create(
                Uuid::uuid4(),
                $messageId,
                $status,
                $error
            )
        );
    }
}
