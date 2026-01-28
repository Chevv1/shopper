<?php

declare(strict_types=1);

namespace App\VendorManagement\Domain\Entity\Product\ProductUnit;

use App\Shared\Domain\ValueObject\StringValue;

final readonly class ProductUnitStatus extends StringValue
{
    private const string STATUS_CODE_AVAILABLE = 'available';

    public static function available(): self
    {
        return new self(self::STATUS_CODE_AVAILABLE);
    }

    protected function validate(): void
    {
    }
}
