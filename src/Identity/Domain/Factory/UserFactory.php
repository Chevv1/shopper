<?php

declare(strict_types=1);

namespace App\Identity\Domain\Factory;

use App\Identity\Domain\Entity\User\HashedPassword;
use App\Identity\Domain\Entity\User\Roles;
use App\Identity\Domain\Entity\User\User;
use App\Identity\Domain\Entity\User\UserCreatedAt;
use App\Identity\Domain\Entity\User\UserEmail;
use App\Identity\Domain\Entity\User\UserId;
use App\Identity\Domain\Entity\User\UserUpdatedAt;

final readonly class UserFactory
{
    public static function create(
        UserEmail      $email,
        HashedPassword $password,
    ): User {
        return new User(
            id: UserId::generate(),
            email: $email,
            password: $password,
            roles: new Roles(['ROLE_USER']),
            createdAt: UserCreatedAt::now(),
            updatedAt: UserUpdatedAt::now(),
        );
    }
}
