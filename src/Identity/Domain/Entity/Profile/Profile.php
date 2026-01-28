<?php

declare(strict_types=1);

namespace App\Identity\Domain\Entity\Profile;

use App\Identity\Domain\Entity\User\UserId;
use App\Shared\Domain\Entity\AggregateRoot;

final class Profile extends AggregateRoot
{
    public function __construct(
        private readonly ProfileId        $id,
        private readonly UserId           $userId,
        private ProfileName               $name,
        private ?ProfileAvatar            $avatar,
        private readonly ProfileCreatedAt $createdAt,
        private ProfileUpdatedAt          $updatedAt,
    ) {}

    // Getters

    public function id(): ProfileId
    {
        return $this->id;
    }

    public function userId(): UserId
    {
        return $this->userId;
    }

    public function name(): ProfileName
    {
        return $this->name;
    }

    public function avatar(): ?ProfileAvatar
    {
        return $this->avatar;
    }

    public function createdAt(): ProfileCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ProfileUpdatedAt
    {
        return $this->updatedAt;
    }
}
