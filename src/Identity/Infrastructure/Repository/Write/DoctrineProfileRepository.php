<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Repository\Write;

use App\Identity\Domain\Entity\Profile\Profile;
use App\Identity\Domain\Repository\ProfileRepositoryInterface;
use Doctrine\DBAL\Connection;

final readonly class DoctrineProfileRepository implements ProfileRepositoryInterface
{
    public function __construct(
        private Connection $connection,
    ) {}

    public function save(Profile $profile): void
    {
        $data = [
            'id' => $profile->id()->value(),
            'user_id' => $profile->userId()->value(),
            'name' => $profile->name()->value(),
            'avatar_id' => $profile->avatar()?->value(),
            'created_at' => $profile->createdAt()->toDateTimeString(),
            'updated_at' => $profile->updatedAt()->toDateTimeString(),
        ];

        $exists = $this->connection->fetchOne(
            query: '
                SELECT COUNT(*)
                FROM profiles
                WHERE id = :id
            ',
            params: ['id' => $profile->id()->value()],
        );

        if ($exists) {
            $this->connection->update('profiles', $data, ['id' => $profile->id()->value()]);
        } else {
            $this->connection->insert('profiles', $data);
        }
    }
}
