<?php

declare(strict_types=1);

namespace App\Chat\Domain\Entity;

final readonly class ChatMember
{
    public function __construct(
        private ChatMemberId       $id,
        private ChatMemberUserId   $userId,
        private ChatMemberRole     $role,
        private ChatMemberJoinedAt $joinedAt,
    ) {}

    // Getters

    public function id(): ChatMemberId
    {
        return $this->id;
    }

    public function userId(): ChatMemberUserId
    {
        return $this->userId;
    }

    public function role(): ChatMemberRole
    {
        return $this->role;
    }

    public function joinedAt(): ChatMemberJoinedAt
    {
        return $this->joinedAt;
    }
}
