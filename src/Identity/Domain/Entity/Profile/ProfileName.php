<?php

declare(strict_types=1);

namespace App\Identity\Domain\Entity\Profile;

use App\Shared\Domain\ValueObject\StringValue;

final readonly class ProfileName extends StringValue
{
    protected function validate(): void
    {
        $nameLength = strlen($this->value);

        if ($nameLength > 50 || $nameLength < 5) {
            throw new \InvalidArgumentException('Profile name must be between 5 and 50 characters');
        }
    }
}
