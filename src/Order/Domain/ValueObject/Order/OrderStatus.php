<?php

declare(strict_types=1);

namespace App\Order\Domain\ValueObject\Order;

use App\Shared\Domain\ValueObject\StringValue;

final readonly class OrderStatus extends StringValue
{
    private const string PENDING = 'pending';
    private const string PAID = 'paid';
    private const string CANCELLED = 'cancelled';

    protected function validate(): void
    {
    }

    public static function pending(): self
    {
        return new self(self::PENDING);
    }

    public static function paid(): self
    {
        return new self(self::PAID);
    }

    public static function cancelled(): self
    {
        return new self(self::CANCELLED);
    }

    public function isPending(): bool
    {
        return $this->value === self::PENDING;
    }
}
