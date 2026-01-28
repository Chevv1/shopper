<?php

declare(strict_types=1);

namespace App\Chat\Domain\Entity;

use App\Shared\Domain\ValueObject\StringValue;

final readonly class ChatStatus extends StringValue
{
    private const string ACTIVE = 'active';
    private const string CLOSED = 'closed';

    public static function active(): self
    {
        return new self(self::ACTIVE);
    }

    public static function closed(): self
    {
        return new self(self::CLOSED);
    }

    protected function validate(): void
    {
    }
}
