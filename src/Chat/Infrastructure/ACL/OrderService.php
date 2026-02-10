<?php

declare(strict_types=1);

namespace App\Chat\Infrastructure\ACL;

use App\Chat\Application\Service\OrderItemSnapshot;
use App\Chat\Application\Service\OrderServiceInterface;
use App\Chat\Application\Service\OrderSnapshot;
use Doctrine\DBAL\Connection;
use Doctrine\DBAL\Exception;

final readonly class OrderService implements OrderServiceInterface
{
    public function __construct(
        private Connection $connection,
    ) {}

    /**
     * @throws Exception
     */
    public function getById(string $id): ?OrderSnapshot
    {
        $orderData = $this->connection->fetchAssociative(
            query: '
                SELECT id
                FROM orders
                WHERE id = ?
            ',
            params: [
                $id,
            ],
        );

        if (empty($orderData)) {
            return null;
        }

        $orderItemsData = $this->connection->fetchAllAssociative(
            query: '
                SELECT
                    order_items.id,
                    products.seller_id
                FROM order_items
                LEFT JOIN products ON products.id = order_items.product_id
                WHERE order_items.order_id = ?
            ',
            params: [
                $id,
            ],
        );

        return new OrderSnapshot(
            id: $orderData['id'],
            items: array_map(
                callback: static fn (array $orderItem) => new OrderItemSnapshot(
                    id: $orderItem['id'],
                    sellerId: $orderItem['seller_id'],
                ),
                array: $orderItemsData,
            ),
        );
    }
}
