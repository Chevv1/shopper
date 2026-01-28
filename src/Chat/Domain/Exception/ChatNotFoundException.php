<?php

declare(strict_types=1);

namespace App\Chat\Domain\Exception;

use App\Chat\Domain\Entity\ChatId;
use App\Shared\Domain\Exception\DomainException;

final class ChatNotFoundException extends DomainException
{
    public static function byId(ChatId $id): self
    {
        return new self("Chat {$id->value()} not found");
    }
}
