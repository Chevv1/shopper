<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Repository\Read;

use App\Catalog\Application\ReadModel\ProductReadModel;
use App\Catalog\Application\ReadModel\ProductReadModelList;
use App\Catalog\Application\Repository\ProductRepositoryInterface;
use App\Catalog\Domain\Entity\ProductId;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

final readonly class CachedProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        private DoctrineProductRepository $decoratedReadRepository,
        private TagAwareCacheInterface    $cache,
    ) {}

    public function getList(): ProductReadModelList
    {
        return $this->cache->get(
            key: 'products.list',
            callback: function (ItemInterface $item) {
                $item->expiresAfter(300);

                return $this->decoratedReadRepository->getList();
            },
        );
    }

    public function getById(ProductId $id): ProductReadModel
    {
        return $this->cache->get(
            key: 'products.' . $id->value(),
            callback: function (ItemInterface $item) use ($id) {
                $item->expiresAfter(300);

                return $this->decoratedReadRepository->getById($id);
            },
        );
    }
}
