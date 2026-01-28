<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Repository\Read;

use App\Identity\Application\ReadModel\ProfileReadModel;
use App\Identity\Application\Repository\ProfileRepositoryInterface;
use App\Identity\Domain\Exception\ProfileNotFound;
use Doctrine\DBAL\Connection;

final readonly class DoctrineProfileRepository implements ProfileRepositoryInterface
{
    public function __construct(
        private Connection $connection,
    ) {}

    public function getByUserId(string $userId): ProfileReadModel
    {
        $qb = $this->connection->createQueryBuilder();

        $data = $qb
            ->select(
                'p.id',
                'p.user_id',
                'p.name',
                'i.filename as avatar_filename',
                'i.path as avatar_path',
                'p.created_at',
                'p.updated_at',
            )
            ->from(table: 'profiles', alias: 'p')
            ->leftJoin(
                fromAlias: 'p',
                join: 'images',
                alias: 'i',
                condition: 'p.avatar_id = i.id',
            )
            ->where('p.user_id = :user_id')
            ->setParameter('user_id', $userId)
            ->executeQuery()
            ->fetchAssociative();

        if ($data === false) {
            throw ProfileNotFound::byUserId($userId);
        }

        return new ProfileReadModel(
            id: $data['id'],
            name: $data['name'],
            avatarUrl: 'uploads/' .$data['avatar_path'] . '/' . $data['avatar_filename'],
        );
    }

    public function getProfileIdByUserId(string $userId): string
    {
        $qb = $this->connection->createQueryBuilder();

        $profileId = $qb
            ->select('p.id')
            ->from(table: 'profiles', alias: 'p')
            ->where('p.user_id = :user_id')
            ->setParameter('user_id', $userId)
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchOne();

        return $profileId ?: throw ProfileNotFound::byUserId($userId);
    }
}
