<?php

declare(strict_types=1);

namespace App\VendorManagement\Domain\Entity\Product;

use App\Shared\Domain\ValueObject\IntegerValue;
use App\VendorManagement\Domain\Exception\ProductPriceCannotBeNegativeException;

final readonly class ProductPrice extends IntegerValue
{
    protected function validate(): void
    {
        if ($this->value < 0) {
            throw new ProductPriceCannotBeNegativeException;
        }
    }
}
