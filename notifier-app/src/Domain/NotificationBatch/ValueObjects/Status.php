<?php

declare(strict_types=1);

namespace Src\Domain\NotificationBatch\ValueObjects;

final class Status
{
    private const PENDING = 'pending';

    private const DISPATCHED = 'dispatched';

    private const COMPLETED = 'completed';

    private const FAILED = 'failed';

    private const ALLOWED = [self::PENDING, self::DISPATCHED, self::COMPLETED, self::FAILED];

    private string $value;

    private function __construct(string $value)
    {
        $this->value = $value;
    }

    public static function pending(): self
    {
        return new self(self::PENDING);
    }

    public static function dispatched(): self
    {
        return new self(self::DISPATCHED);
    }

    public static function completed(): self
    {
        return new self(self::COMPLETED);
    }

    public static function failed(): self
    {
        return new self(self::FAILED);
    }

    public static function fromString(string $value): self
    {
        if (! in_array($value, self::ALLOWED, true)) {
            throw new \InvalidArgumentException('Invalid status value');
        }

        return new self($value);
    }

    public function value(): string
    {
        return $this->value;
    }

    public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    public function canTransitionTo(self $newStatus): bool
    {
        // Бизнес-правила переходов (пример)
        return match ($this->value) {
            self::PENDING => in_array($newStatus->value, [self::DISPATCHED, self::FAILED], true),
            self::DISPATCHED => in_array($newStatus->value, [self::COMPLETED, self::FAILED], true),
            default => false,
        };
    }
}
