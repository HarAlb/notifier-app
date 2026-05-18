<?php

declare(strict_types=1);

namespace Src\Domain\NotificationBatch\ValueObjects;

final class Body
{
    private string $value;

    public function __construct(string $value)
    {
        $value = trim($value);
        if ($value === '') {
            throw new \DomainException('Body cannot be empty');
        }

        if (strlen($value) > 65535) {
            throw new \DomainException('Body too long (max 65535 chars)');
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
