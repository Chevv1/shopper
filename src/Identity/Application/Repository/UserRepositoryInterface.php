<?php

declare(strict_types=1);

namespace App\Identity\Application\Repository;

use App\Identity\Application\ReadModel\UserReadModel;
use App\Identity\Domain\Entity\User\UserEmail;

interface UserRepositoryInterface
{
    public function findByEmail(UserEmail $email): UserReadModel;
}
