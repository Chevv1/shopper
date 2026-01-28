<?php

declare(strict_types=1);

namespace App\Cart\Application\Port;

use App\Cart\Domain\Entity\CartItemProductId;

interface CatalogServiceInterface
{
    public function getProduct(CartItemProductId $productId): ?ProductSnapshot;
}
