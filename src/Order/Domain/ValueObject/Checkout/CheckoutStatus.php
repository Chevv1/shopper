<?php

declare(strict_types=1);

namespace App\Order\Domain\ValueObject\Checkout;

use App\Shared\Domain\ValueObject\StringValue;

final readonly class CheckoutStatus extends StringValue
{
    private const string PENDING = 'pending';
    private const string PAID = 'paid';

    public static function pending(): self
    {
        return new self(self::PENDING);
    }

    public static function paid(): self
    {
        return new self(self::PAID);
    }

    public function isPaid(): bool
    {
        return $this->value === self::PAID;
    }

    protected function validate(): void
    {
    }
}
