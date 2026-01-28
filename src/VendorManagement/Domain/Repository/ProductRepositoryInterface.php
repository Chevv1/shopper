<?php

declare(strict_types=1);

namespace App\VendorManagement\Domain\Repository;

use App\VendorManagement\Domain\Entity\Product\Product;
use App\VendorManagement\Domain\Entity\Product\ProductId;
use App\VendorManagement\Domain\Exception\ProductNotFoundException;

interface ProductRepositoryInterface
{
    /**
     * @throws ProductNotFoundException
     */
    public function getById(ProductId $id): Product;
    public function save(Product $product): void;
}
