<?php

declare(strict_types=1);

namespace App\Cart\Domain\Entity;

use App\Cart\Domain\Exception\QuantityMustBePositiveException;
use App\Shared\Domain\ValueObject\IntegerValue;

final readonly class CartItemQuantity extends IntegerValue
{
    protected function validate(): void
    {
        if ($this->value <= 0) {
            throw new QuantityMustBePositiveException();
        }
    }
}
