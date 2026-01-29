<?php

declare(strict_types=1);

namespace App\Identity\Infrastructure\Repository\Write;

use App\Identity\Domain\Entity\User\HashedPassword;
use App\Identity\Domain\Entity\User\Roles;
use App\Identity\Domain\Entity\User\User;
use App\Identity\Domain\Entity\User\UserCreatedAt;
use App\Identity\Domain\Entity\User\UserEmail;
use App\Identity\Domain\Entity\User\UserId;
use App\Identity\Domain\Entity\User\UserUpdatedAt;
use App\Identity\Domain\Exception\UserNotFound;
use App\Identity\Domain\Repository\UserRepositoryInterface;
use App\Shared\Infrastructure\Persistence\Hydration\ReflectionHydrator;
use Doctrine\DBAL\Connection;

final readonly class DoctrineUserRepository implements UserRepositoryInterface
{
    use ReflectionHydrator;

    public function __construct(
        private Connection $connection
    ) {}

    public function findByEmail(UserEmail $email): User
    {
        $qb = $this->connection->createQueryBuilder();

        $userData = $qb
            ->select(
                'id',
                'email',
                'password',
                'roles',
                'created_at',
                'updated_at',
            )
            ->from('users')
            ->where('email = :email')
            ->setParameter('email', $email->value())
            ->executeQuery()
            ->fetchAssociative();

        if ($userData === false) {
            throw new UserNotFound($email);
        }

        return self::hydrate(
            className: User::class,
            data: [
                'id' => new UserId($userData['id']),
                'email' => new UserEmail($userData['email']),
                'password' => new HashedPassword($userData['password']),
                'roles' => new Roles(json_decode(json: $userData['roles'], associative: true)),
                'createdAt' => UserCreatedAt::fromString($userData['created_at']),
                'updatedAt' => UserUpdatedAt::fromString($userData['updated_at']),
            ],
        );
    }

    public function save(User $user): void
    {
        $updated = $this->connection->update(
            table: 'users',
            data: [
                'email' => $user->email()->value(),
                'password' => $user->password()->value(),
                'roles' => json_encode($user->roles()->value()),
            ],
            criteria: [
                'id' => $user->id()->value(),
            ],
        );

        if ($updated === 0) {
            $this->connection->insert(
                table: 'users',
                data: [
                    'id' => $user->id()->value(),
                    'email' => $user->email()->value(),
                    'password' => $user->password()->value(),
                    'roles' => json_encode($user->roles()->value()),
                    'created_at' => $user->createdAt()->toDateTimeString(),
                    'updated_at' => $user->updatedAt()->toDateTimeString(),
                ],
            );
        }
    }
}
