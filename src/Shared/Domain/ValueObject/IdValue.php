<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use InvalidArgumentException;
use Stringable;
use Symfony\Component\Uid\Uuid;

abstract readonly class IdValue extends ValueObject implements Stringable
{
    public function __construct(protected string $value)
    {
        $this->validate($value);
    }

    final public static function generate(): static
    {
        return new static(Uuid::v4()->toString());
    }

    final public function value(): string
    {
        return $this->value;
    }

    final public function equals(IdValue $other): bool
    {
        return $this->value === $other->value;
    }

    final public function __toString(): string
    {
        return $this->value;
    }

    private function validate(string $value): void
    {
        if (!Uuid::isValid($value)) {
            throw new InvalidArgumentException(
                message: "Invalid UUID format: '$value'. Expected RFC 4122 format.",
            );
        }
    }
}
