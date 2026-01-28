<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

abstract readonly class StringValue extends ValueObject
{
    public function __construct(protected string $value)
    {
        $this->validate();
    }

    abstract protected function validate(): void;

    final public function value(): string
    {
        return $this->value;
    }

    final public function equals(self $string): bool
    {
        return $this->value === $string->value;
    }

    final public function length(): int
    {
        return mb_strlen($this->value);
    }

    final public function isEmpty(): bool
    {
        return $this->value === '';
    }

    final public function __toString(): string
    {
        return $this->value;
    }
}
