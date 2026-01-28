<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

final readonly class Currency extends ValueObject
{
    private function __construct(private string $code)
    {
        if (!preg_match('/^[A-Z]{3}$/', $code)) {
            throw new \DomainException("Invalid currency code: {$code}");
        }
    }

    final public static function fromCode(string $code): self
    {
        return new self(strtoupper($code));
    }

    final public static function USD(): self
    {
        return new self('USD');
    }

    final public static function EUR(): self
    {
        return new self('EUR');
    }

    final public static function RUB(): self
    {
        return new self('RUB');
    }

    final public function code(): string
    {
        return $this->code;
    }

    final public function equals(self $other): bool
    {
        return $this->code === $other->code;
    }
}
