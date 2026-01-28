<?php

declare(strict_types=1);

namespace App\Catalog\Application\Query\GetCategoriesList;

use App\Catalog\Application\ReadModel\CategoryReadModelList;
use App\Catalog\Application\Repository\CategoryRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

final readonly class GetCategoriesListQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private CategoryRepositoryInterface $categoryRepository,
    ) {}

    public function __invoke(GetCategoriesListQuery $query): CategoryReadModelList
    {
        return $this->categoryRepository->findAll();
    }
}
