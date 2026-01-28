<?php

declare(strict_types=1);

namespace App\Chat\Domain\Entity;

use App\Chat\Domain\Exception\ChatMembersCountException;
use App\Shared\Domain\ValueObject\CollectionValue;

final readonly class ChatMembers extends CollectionValue
{
    protected static function itemType(): string
    {
        return ChatMember::class;
    }

    public function __construct(array $items = [])
    {
        parent::__construct($items);

        if ($this->count() < 2) {
            throw ChatMembersCountException::tooFew();
        }
    }

    public function hasMember(ChatMemberId $memberId): bool
    {
        return array_find(
            array: $this->items,
            callback: static fn(ChatMember $item): bool => $item->id()->equals($memberId),
        ) !== null;
    }
}
