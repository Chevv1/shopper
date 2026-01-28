<?php

declare(strict_types=1);

namespace App\Order\Domain\ValueObject\Checkout;

use App\Shared\Domain\ValueObject\IntegerValue;

final readonly class CheckoutTotalAmount extends IntegerValue
{
    protected function validate(): void
    {
    }
}
