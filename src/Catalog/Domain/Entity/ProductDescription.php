<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Entity;

use App\Shared\Domain\ValueObject\StringValue;

final readonly class ProductDescription extends StringValue
{
    protected function validate(): void
    {
    }
}
