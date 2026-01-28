<?php

declare(strict_types=1);

namespace App\Identity\Domain\Service;

use App\Identity\Application\ReadModel\AuthTokenReadModel;
use App\Identity\Domain\Entity\User\RefreshToken;
use App\Identity\Domain\Entity\User\Roles;
use App\Identity\Domain\Entity\User\UserId;

interface TokenGeneratorInterface
{
    public function generate(UserId $userId, ?Roles $roles = null): AuthTokenReadModel;
    public function refresh(RefreshToken $refreshToken): AuthTokenReadModel;
}
