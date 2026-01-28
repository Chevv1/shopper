<?php

declare(strict_types=1);

namespace App\Catalog\Application\Repository;

use App\Catalog\Application\Query\ShowProduct\ShowProductReadModel;
use App\Catalog\Application\ReadModel\ProductReadModel;
use App\Catalog\Application\ReadModel\ProductReadModelList;
use App\Catalog\Domain\Entity\ProductId;

interface ProductRepositoryInterface
{
    public function getList(): ProductReadModelList;
    public function getById(ProductId $id): ProductReadModel;
}
