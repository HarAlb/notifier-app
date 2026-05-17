<?php

declare(strict_types=1);

namespace Src\Domain\NotificationBatch\ValueObjects;

final class Channel
{
    private const ALLOWED = ['email', 'sms'];

    private string $value;

    public function __construct(string $value)
    {
        $value = strtolower(trim($value));
        if (!in_array($value, self::ALLOWED, true)) {
            throw new \InvalidArgumentException('Channel must be either "email" or "sms"');
        }
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function isEmail(): bool
    {
        return $this->value === 'email';
    }

    public function isSms(): bool
    {
        return $this->value === 'sms';
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }
}
