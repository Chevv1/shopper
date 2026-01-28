<?php

declare(strict_types=1);

namespace App\Identity\Domain\Repository;

use App\Identity\Domain\Entity\User\User;
use App\Identity\Domain\Entity\User\UserEmail;

interface UserRepositoryInterface
{
    public function findByEmail(UserEmail $email): User;
    public function save(User $user): void;
}
