<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use DateTimeImmutable;
use DateTimeInterface;
use InvalidArgumentException;

abstract readonly class DateTimeValue extends ValueObject
{
    protected function __construct(
        private DateTimeImmutable $value
    ) {}

    final public static function fromDateTime(DateTimeInterface $dateTime): static
    {
        return new static(DateTimeImmutable::createFromInterface($dateTime));
    }

    final public static function fromString(string $datetime, ?string $format = null): static
    {
        if ($format !== null) {
            $dt = DateTimeImmutable::createFromFormat($format, $datetime);
            if ($dt === false) {
                throw new InvalidArgumentException(
                    sprintf('Invalid datetime format. Expected "%s", got "%s"', $format, $datetime)
                );
            }
            return new static($dt);
        }

        try {
            return new static(new DateTimeImmutable($datetime));
        } catch (\Exception $e) {
            throw new InvalidArgumentException(
                message: sprintf('Invalid datetime string: %s', $datetime),
                code: 0,
                previous: $e,
            );
        }
    }

    final public static function now(): static
    {
        return new static(new DateTimeImmutable());
    }

    final public static function fromTimestamp(int $timestamp): static
    {
        return new static(
            new DateTimeImmutable()->setTimestamp($timestamp)
        );
    }

    final public function value(): DateTimeImmutable
    {
        return $this->value;
    }

    final public function toString(string $format = DateTimeInterface::ATOM): string
    {
        return $this->value->format($format);
    }

    final public function toAtomString(): string
    {
        return $this->value->format(DateTimeInterface::ATOM);
    }

    final public function toDateString(): string
    {
        return $this->value->format('Y-m-d');
    }

    final public function toDateTimeString(): string
    {
        return $this->value->format('Y-m-d H:i:s');
    }

    final public function timestamp(): int
    {
        return $this->value->getTimestamp();
    }

    final public function isAfter(self $other): bool
    {
        return $this->value > $other->value;
    }

    final public function isBefore(self $other): bool
    {
        return $this->value < $other->value;
    }

    final public function equals(self $other): bool
    {
        return $this->value === $other->value;
    }

    final public function diff(self $other): \DateInterval
    {
        return $this->value->diff($other->value);
    }

    final public function modify(string $modifier): static
    {
        return new static($this->value->modify($modifier));
    }

    final public function addDays(int $days): static
    {
        return new static($this->value->modify(sprintf('+%d days', $days)));
    }

    final public function subDays(int $days): static
    {
        return new static($this->value->modify(sprintf('-%d days', $days)));
    }

    final public function __toString(): string
    {
        return $this->toAtomString();
    }
}
