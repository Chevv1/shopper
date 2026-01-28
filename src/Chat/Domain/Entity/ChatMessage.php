<?php

declare(strict_types=1);

namespace App\Chat\Domain\Entity;

final readonly class ChatMessage
{
    public function __construct(
        private ChatMessageId        $id,
        private ChatMemberId         $senderId,
        private ChatMessageContent   $content,
        private ChatMessageCreatedAt $createdAt,
    ) {}

    // Getters

    public function id(): ChatMessageId
    {
        return $this->id;
    }

    public function senderId(): ChatMemberId
    {
        return $this->senderId;
    }

    public function content(): ChatMessageContent
    {
        return $this->content;
    }

    public function createdAt(): ChatMessageCreatedAt
    {
        return $this->createdAt;
    }
}
