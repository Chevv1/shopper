<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\ACL;

use App\Order\Application\Service\Catalog\CatalogServiceInterface;
use App\Order\Application\Service\Catalog\ProductSnapshot;
use App\Order\Domain\Entity\OrderItemPrice;
use App\Order\Domain\Entity\OrderItemProductId;
use App\Order\Domain\Entity\OrderSellerId;
use Doctrine\DBAL\ArrayParameterType;
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
                    price,
                    seller_id
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
                    price,
                    seller_id
                FROM products
                WHERE id IN (?)
            ',
            params: [
                array_map(
                    callback: static fn (OrderItemProductId $productId): string => $productId->value(),
                    array: $productIds,
                ),
            ],
            types: [ArrayParameterType::STRING],
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
            isAvailable: true, // todo
            price: new OrderItemPrice((int) $productData['price']),
            sellerId: new OrderSellerId($productData['seller_id']),
        );
    }
}
