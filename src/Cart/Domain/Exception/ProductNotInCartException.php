<?php

declare(strict_types=1);

namespace App\Cart\Domain\Exception;

use App\Cart\Domain\Entity\CartItemProductId;
use App\Shared\Domain\Exception\DomainException;

final class ProductNotInCartException extends DomainException
{
    public function __construct(CartItemProductId $productId)
    {
        parent::__construct(message: "Product {$productId->value()} not in cart");
    }
}
