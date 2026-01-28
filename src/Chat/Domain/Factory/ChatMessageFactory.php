<?php

declare(strict_types=1);

namespace App\Chat\Domain\Factory;

use App\Chat\Domain\Entity\ChatMemberId;
use App\Chat\Domain\Entity\ChatMessage;
use App\Chat\Domain\Entity\ChatMessageContent;
use App\Chat\Domain\Entity\ChatMessageCreatedAt;
use App\Chat\Domain\Entity\ChatMessageId;

final readonly class ChatMessageFactory
{
    public static function create(
        ChatMemberId $senderId,
        ChatMessageContent $content,
    ): ChatMessage {
        return new ChatMessage(
            id: ChatMessageId::generate(),
            senderId: $senderId,
            content: $content,
            createdAt: ChatMessageCreatedAt::now(),
        );
    }
}
