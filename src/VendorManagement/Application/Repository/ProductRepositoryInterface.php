<?php

declare(strict_types=1);

namespace App\VendorManagement\Application\Repository;

use App\VendorManagement\Application\ReadModel\ProductListReadModel;

interface ProductRepositoryInterface
{
    public function getBySellerId(string $sellerId): ProductListReadModel;
}
