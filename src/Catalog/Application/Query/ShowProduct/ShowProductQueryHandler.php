<?php

declare(strict_types=1);

namespace App\Catalog\Application\Query\ShowProduct;

use App\Catalog\Application\ReadModel\ProductReadModel;
use App\Catalog\Application\Repository\ProductRepositoryInterface;
use App\Catalog\Domain\Entity\ProductId;
use App\Shared\Application\Query\QueryHandlerInterface;

final readonly class ShowProductQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private ProductRepositoryInterface $productReadRepository,
    ) {}

    public function __invoke(ShowProductQuery $query): ProductReadModel
    {
        return $this->productReadRepository->getById(new ProductId($query->productId));
    }
}
