<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\ACL;

use App\Order\Application\Port\Catalog\CatalogServiceInterface;
use App\Order\Application\Port\Catalog\ProductSnapshot;
use App\Order\Domain\ValueObject\Order\OrderItemPrice;
use App\Order\Domain\ValueObject\Order\OrderItemProductId;
use App\Order\Domain\ValueObject\Order\OrderSellerId;
use Doctrine\DBAL\Connection;

final readonly class CatalogService implements CatalogServiceInterface
{
    public function __construct(
        private Connection $connection,
    ) {}

    public function getProduct(OrderItemProductId $productId): ?ProductSnapshot
    {
        $productData = $this->connection->fetchAssociative(
            query: '
                SELECT
                    id,
                    price
                FROM products
                WHERE id = ?
            ',
            params: [$productId],
        );

        if (!$productData) {
            return null;
        }

        return self::hydrate($productData);
    }

    public function getProductsByIds(array $productIds): array
    {
        $productsData = $this->connection->fetchAllAssociative(
            query: '
                SELECT
                    id,
                    price
                FROM products
                WHERE id IN (?)
            ',
            params: [
                array_map(
                    callback: static fn (OrderItemProductId $productId): string => $productId->value(),
                    array: $productIds,
                ),
            ],
        );

        return array_map(
            callback: static fn (array $productData): ProductSnapshot => self::hydrate($productData),
            array: $productsData,
        );
    }

    private static function hydrate(array $productData): ProductSnapshot
    {
        return new ProductSnapshot(
            id: new OrderItemProductId($productData['id']),
            isAvailable: (bool) $productData['is_available'],
            price: new OrderItemPrice($productData['price']),
            sellerId: new OrderSellerId($productData['seller_id']),
        );
    }
}
