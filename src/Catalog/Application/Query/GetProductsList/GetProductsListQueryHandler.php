<?php

declare(strict_types=1);

namespace App\Catalog\Application\Query\GetProductsList;

use App\Catalog\Application\ReadModel\ProductReadModelList;
use App\Catalog\Application\Repository\ProductRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

final readonly class GetProductsListQueryHandler implements QueryHandlerInterface
{
    public function __construct(private ProductRepositoryInterface $productReadRepository)
    {
    }

    public function __invoke(GetProductsListQuery $query): ProductReadModelList
    {
        return $this->productReadRepository->getList();
    }
}
