<?php

declare(strict_types=1);

namespace App\VendorManagement\Domain\Entity\Product;

use App\Shared\Domain\ValueObject\StringValue;

final readonly class ProductTitle extends StringValue
{
    protected function validate(): void
    {
    }
}
