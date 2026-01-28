<?php

declare(strict_types=1);

namespace App\Identity\Domain\Factory;

use App\Identity\Domain\Entity\Profile\Profile;
use App\Identity\Domain\Entity\Profile\ProfileAvatar;
use App\Identity\Domain\Entity\Profile\ProfileCreatedAt;
use App\Identity\Domain\Entity\Profile\ProfileId;
use App\Identity\Domain\Entity\Profile\ProfileName;
use App\Identity\Domain\Entity\Profile\ProfileUpdatedAt;
use App\Identity\Domain\Entity\User\UserId;

final readonly class ProfileFactory
{
    public static function create(
        UserId         $userId,
        ProfileName    $name,
        ?ProfileAvatar $avatarId,
    ): Profile {
        return new Profile(
            id: ProfileId::generate(),
            userId: $userId,
            name: $name,
            avatar: $avatarId,
            createdAt: ProfileCreatedAt::now(),
            updatedAt: ProfileUpdatedAt::now(),
        );
    }
}
