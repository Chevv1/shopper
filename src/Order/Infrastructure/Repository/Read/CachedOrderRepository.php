<?php

declare(strict_types=1);

namespace App\Order\Infrastructure\Repository\Read;

use App\Order\Application\ReadModel\OrderReadModel;
use App\Order\Application\ReadModel\OrderReadModelList;
use App\Order\Application\Repository\OrderRepositoryInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

final readonly class CachedOrderRepository implements OrderRepositoryInterface
{
    public function __construct(
        private DoctrineOrderRepository $decoratedOrderRepository,
        private TagAwareCacheInterface  $cache,
    ) {}

    public function getByCustomerId(string $customerId): OrderReadModelList
    {
        return $this->cache->get(
            key: 'orders.by_profile.' . $customerId,
            callback: function (ItemInterface $item) use ($customerId) {
                $item->expiresAfter(300);

                return $this->decoratedOrderRepository->getByCustomerId($customerId);
            },
        );
    }

    public function getByIdAndCustomerId(string $id, string $customerId): OrderReadModel
    {
        return $this->cache->get(
            key: 'orders.' . $id,
            callback: function (ItemInterface $item) use ($id, $customerId) {
                $item->expiresAfter(300);

                return $this->decoratedOrderRepository->getByIdAndCustomerId($id, $customerId);
            },
        );
    }
}
