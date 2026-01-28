<?php

declare(strict_types=1);

namespace App\Chat\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class ChatParticipantAccessException extends DomainException
{
    public static function notMember(): self
    {
        return new self(
            message: "The user is not a member of this chat."
        );
    }
}
