<?php

declare(strict_types=1);

namespace App\Cart\Domain\Exception;

use App\Cart\Domain\Entity\CartItemProductId;
use App\Shared\Domain\Exception\DomainException;

final class CannotAddToCartException extends DomainException
{
    public static function productNotFound(CartItemProductId $productId): self
    {
        return new self(message: "Product {$productId->value()} not found");
    }

    public static function productIsNotAvailable(CartItemProductId $productId): self
    {
        return new self(message: "Product {$productId->value()} is not available");
    }
}
