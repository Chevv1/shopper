<?php

declare(strict_types=1);

namespace App\Chat\Domain\Entity;

use App\Chat\Domain\Event\ChatCreatedEvent;
use App\Chat\Domain\Exception\ChatParticipantAccessException;
use App\Shared\Domain\Entity\AggregateRoot;

final class Chat extends AggregateRoot
{
    public function __construct(
        private readonly ChatId          $id,
        private readonly ChatCorrelation $correlation,
        private ChatStatus               $status,
        private ChatMessages             $newMessages,
        private ChatMembers              $members,
        private readonly ChatCreatedAt   $createdAt,
        private ChatUpdatedAt            $updatedAt,
    ) {
        $this->recordEvent(new ChatCreatedEvent());
    }

    // Commands

    public function sendMessage(ChatMessage $message): void
    {
        if ($this->members->hasMember(memberId: $message->senderId()) === false) {
            throw ChatParticipantAccessException::notMember();
        }

        $this->newMessages = $this->newMessages->add($message);
    }

    public function addMember(ChatMember $member): void
    {
        if ($this->members->hasMember(memberId: $member->id()) === true) {
            return;
        }

        $this->members = $this->members->add($member);
    }

    public function close(): void
    {
        $this->status = ChatStatus::closed();
        $this->updatedAt = ChatUpdatedAt::now();
    }

    // Getters

    public function id(): ChatId
    {
        return $this->id;
    }

    public function correlation(): ChatCorrelation
    {
        return $this->correlation;
    }

    public function status(): ChatStatus
    {
        return $this->status;
    }

    public function newMessages(): ChatMessages
    {
        return $this->newMessages;
    }

    public function members(): ChatMembers
    {
        return $this->members;
    }

    public function createdAt(): ChatCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): ChatUpdatedAt
    {
        return $this->updatedAt;
    }
}
