<?php

declare(strict_types=1);

namespace Src\Domain\NotificationBatch\Entities;

use DateTimeImmutable;
use Ramsey\Uuid\UuidInterface;
use Src\Domain\NotificationBatch\ValueObjects\MessageStatus;

final readonly class NotificationMessageStatusHistory
{
    private function __construct(
        private UuidInterface $id,
        private UuidInterface $messageId,
        private MessageStatus $status,
        private ?string $error,
        private DateTimeImmutable $createdAt,
    ) {}

    public static function create(
        UuidInterface $id,
        UuidInterface $messageId,
        MessageStatus $status,
        ?string $error,
        ?DateTimeImmutable $createdAt = null,
    ): self {
        return new self(
            $id,
            $messageId,
            $status,
            $error,
            $createdAt ?? new DateTimeImmutable()
        );
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getMessageId(): UuidInterface
    {
        return $this->messageId;
    }

    public function getStatus(): MessageStatus
    {
        return $this->status;
    }

    public function getError(): ?string
    {
        return $this->error;
    }

    public function getCreatedAt(): DateTimeImmutable
    {
        return $this->createdAt;
    }
}
