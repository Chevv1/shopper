<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Entity;

use App\Catalog\Domain\Exception\ProductPriceCannotBeNegative;
use App\Shared\Domain\ValueObject\IntegerValue;

final readonly class ProductPrice extends IntegerValue
{
    protected function validate(): void
    {
        if ($this->value < 0) {
            throw new ProductPriceCannotBeNegative;
        }
    }
}
