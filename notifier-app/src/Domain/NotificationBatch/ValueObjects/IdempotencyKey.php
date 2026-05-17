<?php

declare(strict_types=1);

namespace Src\Domain\NotificationBatch\ValueObjects;

final class IdempotencyKey
{
    private string $value;

    public function __construct(string $value)
    {
        $value = trim($value);
        if ($value === '') {
            throw new \DomainException('Idempotency key cannot be empty');
        }
        if (strlen($value) > 255) {
            throw new \DomainException('Idempotency key too long, max 255 chars');
        }
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
