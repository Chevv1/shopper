<?php

declare(strict_types=1);

namespace App\Chat\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class ChatMembersCountException extends DomainException
{
    public static function tooFew(): self
    {
        return new self('The chat must have at least 2 members');
    }
}
