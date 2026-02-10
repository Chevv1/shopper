<?php

declare(strict_types=1);

namespace App\Order\Domain\Entity;

use App\Order\Domain\Exception\OrderItemPriceIsNegativeException;
use App\Shared\Domain\ValueObject\IntegerValue;

final readonly class OrderItemPrice extends IntegerValue
{
    protected function validate(): void
    {
        if ($this->value < 0) {
            throw new OrderItemPriceIsNegativeException();
        }
    }
}
