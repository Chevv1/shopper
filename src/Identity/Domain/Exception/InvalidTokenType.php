<?php

declare(strict_types=1);

namespace App\Identity\Domain\Exception;

final class InvalidTokenType extends \InvalidArgumentException
{
    public function __construct()
    {
        parent::__construct(
            message: 'Invalid token type',
        );
    }
}
