<?php

declare(strict_types=1);

namespace App\Chat\Domain\Entity;

use App\Shared\Domain\ValueObject\CollectionValue;

final readonly class ChatMessages extends CollectionValue
{
    protected static function itemType(): string
    {
        return ChatMessage::class;
    }
}
