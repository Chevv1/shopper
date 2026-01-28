<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

abstract readonly class ArrayValue extends ValueObject
{
    public function __construct(protected array $value) {}

    final public function value(): array
    {
        return $this->value;
    }

    final public function isEmpty(): bool
    {
        return count($this->value) === 0;
    }

    final public function count(): int
    {
        return count($this->value);
    }

    final public function unique(): static
    {
        return new static(array_unique($this->value));
    }

    final public function diff(self $diffArray): static
    {
        return new static(array_diff($this->value, $diffArray->value));
    }
}
