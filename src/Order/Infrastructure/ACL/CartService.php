<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\ACL;

use App\Order\Application\Port\Cart\CartItemSnapshot;
use App\Order\Application\Port\Cart\CartServiceInterface;
use App\Order\Application\Port\Cart\CartSnapshot;
use App\Order\Domain\ValueObject\Order\OrderCustomerId;
use App\Order\Domain\ValueObject\Order\OrderId;
use App\Order\Domain\ValueObject\Order\OrderItemProductId;
use App\Order\Domain\ValueObject\Order\OrderItemQuantity;
use Doctrine\DBAL\Connection;

final readonly class CartService implements CartServiceInterface
{
    public function __construct(
        private Connection $connection,
    ) {}

    public function getCustomerCart(OrderCustomerId $customer): ?CartSnapshot
    {
        $cartData = $this->connection->fetchAssociative(
            query: '
                SELECT
                    id,
                    user_id
                FROM carts 
                WHERE user_id = ?
            ',
            params: [$customer->value()]
        );

        if (!$cartData) {
            return null;
        }

        $items = $this->connection->fetchAllAssociative(
            query: '
                SELECT 
                    ci.product_id,
                    ci.quantity
                FROM cart_items ci
                WHERE ci.cart_id = ?
            ',
            params: [$cartData['id']],
        );

        return new CartSnapshot(
            id: new OrderId($cartData['id']),
            customerId: new OrderCustomerId($cartData['user_id']),
            items: array_map(
                callback: static fn(array $cartItem) => new CartItemSnapshot(
                    productId: new OrderItemProductId($cartItem['product_id']),
                    quantity: new OrderItemQuantity($cartItem['quantity']),
                ),
                array: $items,
            )
        );
    }
}
