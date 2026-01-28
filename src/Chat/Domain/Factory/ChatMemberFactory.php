<?php

declare(strict_types=1);

namespace App\Chat\Domain\Factory;

use App\Chat\Domain\Entity\ChatMember;
use App\Chat\Domain\Entity\ChatMemberId;
use App\Chat\Domain\Entity\ChatMemberJoinedAt;
use App\Chat\Domain\Entity\ChatMemberRole;
use App\Chat\Domain\Entity\ChatMemberUserId;

final readonly class ChatMemberFactory
{
    public static function create(
        ChatMemberUserId $userId,
        ChatMemberRole $role,
    ): ChatMember {
        return new ChatMember(
            id: ChatMemberId::generate(),
            userId: $userId,
            role: $role,
            joinedAt: ChatMemberJoinedAt::now(),
        );
    }

    public static function createSeller(ChatMemberUserId $userId): ChatMember
    {
        return self::create(
            userId: $userId,
            role: ChatMemberRole::Seller(),
        );
    }

    public static function createCustomer(ChatMemberUserId $userId): ChatMember
    {
        return self::create(
            userId: $userId,
            role: ChatMemberRole::Customer(),
        );
    }
}
