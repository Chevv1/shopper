<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Repository\Read;

use App\Identity\Application\ReadModel\ProfileReadModel;
use App\Identity\Application\Repository\ProfileRepositoryInterface;
use Symfony\Contracts\Cache\ItemInterface;
use Symfony\Contracts\Cache\TagAwareCacheInterface;

final readonly class CachedProfileRepository implements ProfileRepositoryInterface
{
    public function __construct(
        private DoctrineProfileRepository $decoratedProfileReadRepository,
        private TagAwareCacheInterface    $cache,
    )
    {
    }

    public function getByUserId(string $userId): ProfileReadModel
    {
        return $this->cache->get(
            key: 'profiles.by_user_id.' . $userId,
            callback: function (ItemInterface $item) use ($userId) {
                $item->expiresAfter(300);

                return $this->decoratedProfileReadRepository->getByUserId($userId);
            },
        );
    }

    public function getProfileIdByUserId(string $userId): string
    {
        return $this->cache->get(
            key: 'profiles.ids.by_user_id.' . $userId,
            callback: function (ItemInterface $item) use ($userId) {
                $item->expiresAfter(300);

                return $this->decoratedProfileReadRepository->getProfileIdByUserId($userId);
            },
        );
    }
}
