<?php

declare(strict_types=1);

namespace App\Catalog\Presentation\Controller;

use App\Catalog\Application\Query\GetProductsList\GetProductsListQuery;
use App\Catalog\Application\Query\ShowProduct\ShowProductQuery;
use App\Shared\Application\Bus\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

final class ProductController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {}

    public function listProducts(): JsonResponse
    {
        $products = $this->queryBus->ask(new GetProductsListQuery());

        return $this->json(
            data: [
                'data' => $products->toArray(),
            ],
        );
    }

    public function showProduct(string $id): JsonResponse
    {
        $product = $this->queryBus->ask(new ShowProductQuery(
            productId: $id,
        ));

        return $this->json(
            data: $product->toArray(),
        );
    }
}
