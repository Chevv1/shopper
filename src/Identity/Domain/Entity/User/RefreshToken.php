<?php

declare(strict_types=1);

namespace App\Identity\Domain\Entity\User;

use App\Shared\Domain\ValueObject\StringValue;

final readonly class RefreshToken extends StringValue
{
    protected function validate(): void
    {
    }
}
