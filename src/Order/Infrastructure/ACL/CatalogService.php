<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\ACL;

use App\Order\Application\Port\Catalog\CatalogServiceInterface;
use App\Order\Application\Port\Catalog\ProductSnapshot;
use App\Order\Domain\ValueObject\Order\OrderItemPrice;
use App\Order\Domain\ValueObject\Order\OrderItemProductId;
use Doctrine\DBAL\Connection;

final readonly class CatalogService implements CatalogServiceInterface
{
    public function __construct(
        private Connection $connection,
    ) {}

    public function getProduct(OrderItemProductId $productId): ?ProductSnapshot
    {
        $data = $this->connection->fetchAssociative(
            query: '
                SELECT
                    id,
                    price
                FROM products
                WHERE id = ?
            ',
            params: [$productId],
        );

        if (!$data) {
            return null;
        }

        return new ProductSnapshot(
            id: new OrderItemProductId($data['id']),
            isAvailable: true,
            price: new OrderItemPrice($data['price']),
        );
    }
}
