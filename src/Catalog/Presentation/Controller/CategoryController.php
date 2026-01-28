<?php

declare(strict_types=1);

namespace App\Catalog\Presentation\Controller;

use App\Catalog\Application\Query\GetCategoriesList\GetCategoriesListQuery;
use App\Catalog\Application\ReadModel\CategoryReadModelList;
use App\Shared\Application\Bus\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

final class CategoryController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {}

    public function listCategories(): JsonResponse
    {
        $query = new GetCategoriesListQuery();

        /** @var CategoryReadModelList $categories */
        $categories = $this->queryBus->ask($query);

        return $this->json(
            data: $categories->toArray(),
        );
    }
}
