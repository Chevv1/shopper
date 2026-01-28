<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use DomainException;

final readonly class Money
{
    private function __construct(
        private float $amount,
    ) {
        if ($amount < 0) {
            throw new DomainException('Amount cannot be negative');
        }
    }

    final public static function fromAmount(float $amount): self
    {
        return new self($amount);
    }

    final public function amount(): float
    {
        return $this->amount;
    }

    final public function multiply(int $multiplier): self
    {
        return new self($this->amount * $multiplier);
    }

    final public function add(Money $other): self
    {
        return new self($this->amount + $other->amount);
    }

    final public function equals(Money $other): bool
    {
        return $this->amount === $other->amount;
    }
}
