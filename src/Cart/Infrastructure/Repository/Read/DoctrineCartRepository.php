<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure\Repository\Read;

use App\Cart\Application\ReadModel\CartItemReadModel;
use App\Cart\Application\ReadModel\CartReadModel;
use App\Cart\Application\Repository\CartRepositoryInterface;
use Doctrine\DBAL\Connection;

final readonly class DoctrineCartRepository implements CartRepositoryInterface
{
    public function __construct(
        public Connection $connection,
    ) {}

    public function getByOwnerId(string $ownerId): CartReadModel
    {
        $cartData = $this->connection->fetchAssociative(
            query: '
                SELECT
                    id
                FROM carts 
                WHERE user_id = ?
            ',
            params: [$ownerId]
        );

        if (empty($cartData)) {
            return new CartReadModel(
                items: [],
                totalAmount: 0,
                totalItems: 0,
            );
        }

        $items = $this->connection->fetchAllAssociative(
            query: '
                SELECT 
                    p.id as product_id,
                    p.title as product_title,
                    ci.quantity,
                    ci.price,
                    p.is_available
                FROM cart_items ci
                LEFT JOIN products p ON ci.product_id = p.id
                WHERE ci.cart_id = ?
            ',
            params: [$cartData['id']],
        );

        $cartItems = [];
        $totalAmount = $totalItems = 0;

        foreach ($items as $item) {
            $price = (int) $item['price'];
            $quantity = (int) $item['quantity'];
            $isAvailable = (bool) $item['is_available'];

            $cartItems[] = new CartItemReadModel(
                productId: $item['product_id'],
                productTitle: $item['product_title'],
                quantity: $quantity,
                unitPrice: $price,
                totalPrice: $price * $quantity,
                isAvailable: $isAvailable,
            );

            if ($isAvailable) {
                $totalAmount += $price * $quantity;
                $totalItems += $quantity;
            }
        }

        return new CartReadModel(
            items: $cartItems,
            totalAmount: $totalAmount,
            totalItems: $totalItems,
        );
    }
}
