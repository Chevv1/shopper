<?php

declare(strict_types=1);

namespace App\Identity\Domain\Entity\User;

use App\Shared\Domain\ValueObject\StringValue;

final readonly class HashedPassword extends StringValue
{
    protected function validate(): void
    {
    }
}
