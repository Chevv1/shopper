<?php

declare(strict_types=1);

namespace App\Order\Domain\Exception;

use App\Order\Domain\ValueObject\Order\OrderCustomerId;
use App\Order\Domain\ValueObject\Order\OrderItemProductId;
use App\Shared\Domain\Exception\DomainException;

final class CannotPlaceOrderException extends DomainException
{
    public static function emptyOrder(): self
    {
        return new self("Order is empty");
    }

    public static function productNotFound(OrderItemProductId $productId): self
    {
        return new self("Product {$productId->value()} not found");
    }

    public static function productUnavailable(OrderItemProductId $productId): self
    {
        return new self("Product {$productId->value()} is not available");
    }

    public static function cannotReserveProduct(OrderItemProductId $productId): self
    {
        return new self("Cannot reserve product {$productId->value()}");
    }

    public static function customerDoesntHaveCart(OrderCustomerId $customerId): self
    {
        return new self("Customer {$customerId->value()} does not have a cart yet");
    }
}
