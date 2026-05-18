<?php

declare(strict_types=1);

namespace Src\Domain\NotificationBatch\Entities;

use Ramsey\Collection\Collection;
use Ramsey\Uuid\UuidInterface;
use Src\Domain\NotificationBatch\ValueObjects\Body;
use Src\Domain\NotificationBatch\ValueObjects\Channel;
use Src\Domain\NotificationBatch\ValueObjects\IdempotencyKey;
use Src\Domain\NotificationBatch\ValueObjects\Priority;
use Src\Domain\NotificationBatch\ValueObjects\Status;
use Src\Domain\NotificationBatch\ValueObjects\Subject;

final class NotificationBatch
{
    private UuidInterface $id;

    private IdempotencyKey $idempotencyKey;

    private Channel $channel;

    private ?Subject $subject;

    private Body $body;

    private Priority $priority;

    private Status $status;

    private \DateTimeImmutable $createdAt;

    private \DateTimeImmutable $updatedAt;

    private Collection $messages;

    private function __construct(
        UuidInterface $id,
        IdempotencyKey $idempotencyKey,
        Channel $channel,
        Body $body,
        Priority $priority,
        Status $status,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt,
        ?Subject $subject = null,
    ) {
        $this->id = $id;
        $this->idempotencyKey = $idempotencyKey;
        $this->channel = $channel;
        $this->subject = $subject;
        $this->body = $body;
        $this->priority = $priority;
        $this->status = $status;
        $this->createdAt = $createdAt;
        $this->updatedAt = $updatedAt;

        $this->messages = new Collection(NotificationMessage::class);
    }

    public static function restore(
        UuidInterface $id,
        IdempotencyKey $idempotencyKey,
        Channel $channel,
        Body $body,
        Priority $priority,
        Status $status,
        \DateTimeImmutable $createdAt,
        \DateTimeImmutable $updatedAt,
        ?Subject $subject = null,
    ): self {
        return new self(
            $id,
            $idempotencyKey,
            $channel,
            $body,
            $priority,
            $status,
            $createdAt,
            $updatedAt,
            $subject,
        );
    }

    public static function create(
        UuidInterface $id,
        IdempotencyKey $idempotencyKey,
        Channel $channel,
        Body $body,
        ?Priority $priority = null,
        ?Subject $subject = null,
    ): self {
        $now = new \DateTimeImmutable;

        return new self(
            $id,
            $idempotencyKey,
            $channel,
            $body,
            $priority ?? Priority::MARKETING,
            Status::pending(),
            $now,
            $now,
            $subject,
        );
    }

    public function setId(?UuidInterface $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getIdempotencyKey(): IdempotencyKey
    {
        return $this->idempotencyKey;
    }

    public function setIdempotencyKey(IdempotencyKey $idempotencyKey): NotificationBatch
    {
        $this->idempotencyKey = $idempotencyKey;

        return $this;
    }

    public function getStatus(): Status
    {
        return $this->status;
    }

    public function markAsDispatched(): void
    {
        $newStatus = Status::dispatched();

        if (! $this->status->canTransitionTo($newStatus)) {
            throw new \DomainException(
                sprintf('Cannot transition from %s to %s', $this->status->value(), $newStatus->value())
            );
        }

        $this->status = $newStatus;
        $this->updatedAt = new \DateTimeImmutable;
    }

    public function markAsCompleted(): void
    {
        $newStatus = Status::completed();

        if (! $this->status->canTransitionTo($newStatus)) {
            throw new \DomainException(
                sprintf('Cannot transition from %s to %s', $this->status->value(), $newStatus->value())
            );
        }

        $this->status = $newStatus;
        $this->updatedAt = new \DateTimeImmutable;
    }

    public function markAsFailed(): void
    {
        $newStatus = Status::failed();

        if (! $this->status->canTransitionTo($newStatus)) {
            throw new \DomainException(
                sprintf('Cannot transition from %s to %s', $this->status->value(), $newStatus->value())
            );
        }

        $this->status = $newStatus;
        $this->updatedAt = new \DateTimeImmutable;
    }

    public function getPriority(): Priority
    {
        return $this->priority;
    }

    public function getChannel(): Channel
    {
        return $this->channel;
    }

    public function getId(): UuidInterface
    {
        return $this->id;
    }

    public function getSubject(): ?Subject
    {
        return $this->subject;
    }

    public function getBody(): Body
    {
        return $this->body;
    }

    public function getCreatedAt(): \DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function addMessage(NotificationMessage $message): self
    {
        $this->messages->add($message);

        return $this;
    }

    public function getMessages(): Collection
    {
        return $this->messages;
    }
}
