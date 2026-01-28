<?php

declare(strict_types=1);

namespace App\Catalog\Application\Repository;

use App\Catalog\Application\ReadModel\CategoryReadModelList;

interface CategoryRepositoryInterface
{
    public function findAll(): CategoryReadModelList;
}
