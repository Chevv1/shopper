<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Repository\Read;

use App\Identity\Application\ReadModel\UserReadModel;
use App\Identity\Application\Repository\UserRepositoryInterface;
use App\Identity\Domain\Entity\User\UserEmail;
use App\Identity\Domain\Exception\UserNotFound;
use Doctrine\DBAL\Connection;

final readonly class DoctrineUserRepository implements UserRepositoryInterface
{
    public function __construct(
        private Connection $connection
    ) {}

    public function findByEmail(UserEmail $email): UserReadModel
    {
        $qb = $this->connection->createQueryBuilder();

        $data = $qb
            ->select(
                'id',
                'email',
                'roles',
            )
            ->from('users')
            ->where('email = :email')
            ->setParameter('email', $email->value())
            ->executeQuery()
            ->fetchAssociative();

        if ($data === false) {
            throw new UserNotFound($email);
        }

        return new UserReadModel(
            id: $data['id'],
            email: $data['email'],
            roles: json_decode($data['roles'], true),
        );
    }
}
