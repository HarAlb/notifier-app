<?php

declare(strict_types=1);

namespace Src\Domain\NotificationBatch\Entities;

use Ramsey\Uuid\UuidInterface;
use Src\Domain\NotificationBatch\ValueObjects\MessageStatus;

final class NotificationMessage
{
    private UuidInterface $id;

    private UuidInterface $batchId;

    private int $recipientId;

    private MessageStatus $status;

    private int $attempts;

    private ?string $lastError;

    private \DateTimeImmutable $createdAt;

    private \DateTimeImmutable $updatedAt;

    private function __construct(
        UuidInterface $id,
        UuidInterface $batchId,
        int $recipientId,
        MessageStatus $status,
        int $attempts,
        ?string $lastError,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt
    ) {
        $this->id = $id;
        $this->batchId = $batchId;
        $this->recipientId = $recipientId;
        $this->status = $status;
        $this->attempts = $attempts;
        $this->lastError = $lastError;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;
    }

    public static function createForBatch(
        UuidInterface $id,
        UuidInterface $batchId,
        int $recipientId
    ): self {
        $now = new \DateTimeImmutable;

        return new self(
            $id,
            $batchId,
            $recipientId,
            MessageStatus::PENDING,
            0,
            null,
            $now,
            $now
        );
    }

    public static function fromDatabase(
        UuidInterface $id,
        UuidInterface $batchId,
        int $recipientId,
        MessageStatus $status,
        int $attempts,
        ?string $lastError,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt
    ): self {
        return new self(
            $id,
            $batchId,
            $recipientId,
            $status,
            $attempts,
            $lastError,
            $createdAt,
            $updatedAt
        );
    }

    public function markAsPending(): void
    {
        $this->status = MessageStatus::PENDING;
        $this->updatedAt = new \DateTimeImmutable;
    }

    public function markAsProcessing(): void
    {
        if ($this->status !== MessageStatus::PENDING) {
            return; // already taken
        }

        $this->status = MessageStatus::PROCESSING;
        $this->updatedAt = new \DateTimeImmutable;
    }

    public function markAsSent(): void
    {
        if ($this->status !== MessageStatus::PROCESSING) {
            return;
        }

        $this->status = MessageStatus::SENT;
        $this->updatedAt = new \DateTimeImmutable;
    }

    public function markAsFailed(string $error): void
    {
        if ($this->status === MessageStatus::SENT) {
            return;
        }

        $this->status = MessageStatus::FAILED;
        $this->attempts++;
        $this->lastError = $error;
        $this->updatedAt = new \DateTimeImmutable;
    }

    public function incrementAttempts(string $error): void
    {
        $this->attempts++;
        $this->lastError = $error;
        $this->updatedAt = new \DateTimeImmutable;
    }

    // Getters
    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getBatchId(): UuidInterface
    {
        return $this->batchId;
    }

    public function getRecipientId(): int
    {
        return $this->recipientId;
    }

    public function isProcessed(): bool
    {
        return in_array($this->status, [
            MessageStatus::SENT,
            MessageStatus::FAILED,
        ]);
    }

    public function getStatus(): MessageStatus
    {
        return $this->status;
    }

    public function getAttempts(): int
    {
        return $this->attempts;
    }

    public function getLastError(): ?string
    {
        return $this->lastError;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }
}
