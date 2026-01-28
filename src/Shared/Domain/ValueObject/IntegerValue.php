<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

abstract readonly class IntegerValue extends ValueObject
{
    final public function __construct(protected int $value)
    {
        $this->validate();
    }

    abstract protected function validate(): void;

    final public function value(): int
    {
        return $this->value;
    }

    final public function add(int $amount): self
    {
        return new static($amount + $this->value);
    }

    final public function multiply(int $multiplier): self
    {
        return new static($multiplier * $this->value);
    }

    final public function greaterThan(self $other): bool
    {
        return $this->value > $other->value;
    }

    final public function lessThan(self $other): bool
    {
        return $this->value < $other->value;
    }

    final public function __toString(): string
    {
        return (string) $this->value;
    }
}
