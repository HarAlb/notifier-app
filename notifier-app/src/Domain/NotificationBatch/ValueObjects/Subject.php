<?php

declare(strict_types=1);

namespace Src\Domain\NotificationBatch\ValueObjects;

final class Subject
{
    private string $value;

    public function __construct(string $value)
    {
        $value = trim($value);
        if ($value === '') {
            throw new \InvalidArgumentException('Subject cannot be empty string (use null instead)');
        }
        if (strlen($value) > 255) {
            throw new \InvalidArgumentException('Subject too long, max 255 chars');
        }
        $this->value = $value;
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(?self $other): bool
    {
        if ($other === null) {
            return false;
        }
        return $this->value === $other->value;
    }
}
