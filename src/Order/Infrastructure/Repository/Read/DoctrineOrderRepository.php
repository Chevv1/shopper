<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Repository\Read;

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
                    createdAt: $row['created_at'],
                    updatedAt: $row['updated_at'],
                ),
                array: $rows,
            ),
        );
    }
}
