<?php

declare(strict_types=1);

namespace App\Cart\Infrastructure\ACL;

use App\Cart\Application\Port\CatalogServiceInterface;
use App\Cart\Application\Port\ProductSnapshot;
use App\Cart\Domain\Entity\CartItemProductId;
use App\Shared\Domain\ValueObject\Money;
use Doctrine\DBAL\Connection;

final readonly class CatalogService implements CatalogServiceInterface
{
    public function __construct(
        private Connection $connection,
    ) {}

    public function getProduct(CartItemProductId $productId): ?ProductSnapshot
    {
        $data = $this->connection->fetchAssociative(
            query: '
                SELECT id, price, is_available 
                FROM products 
                WHERE id = ?',
            params: [$productId],
        );

        if (!$data) {
            return null;
        }

        return new ProductSnapshot(
            id: new CartItemProductId($data['id']),
            isAvailable: (bool) $data['is_available'],
            price: Money::fromAmount(
                amount: (float) $data['price'],
            ),
        );
    }
}
