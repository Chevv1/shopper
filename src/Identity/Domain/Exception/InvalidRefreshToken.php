<?php

declare(strict_types=1);

namespace App\Identity\Domain\Exception;

final class InvalidRefreshToken extends \InvalidArgumentException
{
    public function __construct(string $message)
    {
        parent::__construct(
            message: 'Invalid refresh token: ' . $message,
        );
    }
}
