<?php

declare(strict_types=1);

namespace App\Chat\Domain\Factory;

use App\Chat\Domain\Entity\Chat;
use App\Chat\Domain\Entity\ChatCorrelation;
use App\Chat\Domain\Entity\ChatCreatedAt;
use App\Chat\Domain\Entity\ChatId;
use App\Chat\Domain\Entity\ChatMembers;
use App\Chat\Domain\Entity\ChatMessages;
use App\Chat\Domain\Entity\ChatStatus;
use App\Chat\Domain\Entity\ChatUpdatedAt;

final readonly class ChatFactory
{
    public static function create(
        ChatCorrelation $correlation,
        ChatMembers     $members,
    ): Chat {
        return new Chat(
            id: ChatId::generate(),
            correlation: $correlation,
            status: ChatStatus::active(),
            newMessages: new ChatMessages(),
            members: $members,
            createdAt: ChatCreatedAt::now(),
            updatedAt: ChatUpdatedAt::now(),
        );
    }
}
