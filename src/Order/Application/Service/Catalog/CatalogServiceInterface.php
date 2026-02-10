<?php

declare(strict_types=1);

namespace App\Order\Application\Service\Catalog;

use App\Order\Domain\Entity\OrderItemProductId;

interface CatalogServiceInterface
{
    public function getProduct(OrderItemProductId $productId): ?ProductSnapshot;

    /**
     * @param OrderItemProductId[] $productIds
     * @return ProductSnapshot[]
     */
    public function getProductsByIds(array $productIds): array;
}
