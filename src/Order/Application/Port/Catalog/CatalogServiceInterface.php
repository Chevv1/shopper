<?php

declare(strict_types=1);

namespace App\Order\Application\Port\Catalog;

use App\Order\Domain\ValueObject\Order\OrderItemProductId;

interface CatalogServiceInterface
{
    public function getProduct(OrderItemProductId $productId): ?ProductSnapshot;

    /** @var OrderItemProductId[] $productIds */
    public function getProductsByIds(array $productIds);
}
