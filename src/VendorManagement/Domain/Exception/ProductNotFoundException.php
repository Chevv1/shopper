<?php

declare(strict_types=1);

namespace App\VendorManagement\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class ProductNotFoundException extends DomainException
{
    public static function byId(string $value): self
    {
        return new self(
            message: sprintf('Product with id "%s" not found.', $value),
        );
    }
}
