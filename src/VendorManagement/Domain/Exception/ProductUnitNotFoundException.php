<?php

declare(strict_types=1);

namespace App\VendorManagement\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class ProductUnitNotFoundException extends DomainException
{
    public static function byId(string $value): self
    {
        return new self(message: "Product unit with id {$value} not found");
    }
}
