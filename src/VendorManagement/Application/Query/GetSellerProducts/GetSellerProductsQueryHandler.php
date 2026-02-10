<?php

declare(strict_types=1);

namespace App\VendorManagement\Application\Query\GetSellerProducts;

use App\Shared\Application\Query\QueryHandlerInterface;
use App\VendorManagement\Application\ReadModel\ProductListReadModel;
use App\VendorManagement\Application\Repository\ProductRepositoryInterface;

final readonly class GetSellerProductsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
    ) {}

    public function __invoke(GetSellerProductsQuery $query): ProductListReadModel
    {
        return $this->productRepository->getBySellerId(sellerId: $query->sellerId);
    }
}
