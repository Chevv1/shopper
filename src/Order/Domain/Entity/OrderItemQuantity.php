<?php

declare(strict_types=1);

namespace App\Order\Domain\Entity;

use App\Order\Domain\Exception\OrderQuantityIsNegativeException;
use App\Order\Domain\Exception\OrderQuantityIsZeroException;
use App\Shared\Domain\ValueObject\IntegerValue;

final readonly class OrderItemQuantity extends IntegerValue
{
    protected function validate(): void
    {
        if ($this->value === 0) {
            throw new OrderQuantityIsZeroException();
        }

        if ($this->value < 0) {
            throw new OrderQuantityIsNegativeException();
        }
    }
}
