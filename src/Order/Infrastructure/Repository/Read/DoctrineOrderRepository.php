<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Repository\Read;

use App\Order\Application\ReadModel\OrderItemReadModel;
use App\Order\Application\ReadModel\OrderReadModel;
use App\Order\Application\ReadModel\OrderReadModelList;
use App\Order\Application\Repository\OrderRepositoryInterface;
use App\Order\Domain\Exception\OrderNotFoundException;
use Doctrine\DBAL\Connection;

final readonly class DoctrineOrderRepository implements OrderRepositoryInterface
{
    public function __construct(
        private Connection $connection,
    ) {}

    public function getByCustomerId(string $customerId): OrderReadModelList
    {
        $rows = $this->connection->fetchAllAssociative(
            query: '
                SELECT
                    id,
                    status,
                    total_price,
                    created_at,
                    updated_at
                FROM orders
                WHERE customer_id = ?
                ORDER BY created_at DESC
            ',
            params: [$customerId],
        );

        return new OrderReadModelList(
            orders: array_map(
                callback: static fn(array $row) => new OrderReadModel(
                    id: $row['id'],
                    status: $row['status'],
                    total: $row['total_price'],
                    items: [],
                    createdAt: $row['created_at'],
                    updatedAt: $row['updated_at'],
                ),
                array: $rows,
            ),
        );
    }

    public function getByIdAndCustomerId(string $id, string $customerId): OrderReadModel
    {
        $orderData = $this->connection->fetchAssociative(
            query: '
                SELECT
                    id,
                    status,
                    total_price,
                    created_at,
                    updated_at
                FROM orders
                WHERE
                    id = :id AND
                    customer_id = :customerId
                ORDER BY created_at DESC
            ',
            params: [
                'id' => $id,
                'customerId' => $customerId,
            ],
        );

        if (empty($orderData)) {
            throw new OrderNotFoundException();
        }

        $orderItemsData = $this->connection->fetchAllAssociative(
            query: '
                SELECT
                    order_items.id,
                    order_items.product_id,
                    products.title AS product_name,
                    order_items.quantity,
                    order_items.price
                FROM
                    order_items
                JOIN
                    products ON products.id = order_items.product_id
                WHERE
                    order_id = :orderId
                ORDER BY created_at DESC
            ',
            params: [
                'orderId' => $id,
            ],
        );

        return new OrderReadModel(
            id: $orderData['id'],
            status: $orderData['status'],
            total: $orderData['total_price'],
            items: array_map(
                callback: static fn(array $row) => new OrderItemReadModel(
                    id: $row['id'],
                    productId: $row['product_id'],
                    productName: $row['product_name'],
                    quantity: $row['quantity'],
                    price: $row['price'],
                ),
                array: $orderItemsData,
            ),
            createdAt: $orderData['created_at'],
            updatedAt: $orderData['updated_at'],
        );
    }
}
