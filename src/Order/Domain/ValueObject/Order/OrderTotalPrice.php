<?php

declare(strict_types=1);

namespace App\Order\Domain\ValueObject\Order;

use App\Order\Domain\Exception\OrderTotalPriceMustBeGreaterThenZeroException;
use App\Shared\Domain\ValueObject\IntegerValue;

final readonly class OrderTotalPrice extends IntegerValue
{
    protected function validate(): void
    {
        if ($this->value <= 0) {
            throw new OrderTotalPriceMustBeGreaterThenZeroException;
        }
    }
}
