<?php

declare(strict_types=1);

namespace App\Identity\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class ProfileNotFound extends DomainException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function byUserId(string $userId): self
    {
        return new self("Profile by userId: {$userId} not found");
    }
}
