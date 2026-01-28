<?php

declare(strict_types=1);

namespace App\Chat\Domain\Entity;

use App\Shared\Domain\ValueObject\StringValue;
use InvalidArgumentException;

final readonly class ChatCorrelationType extends StringValue
{
    private const string ORDER = 'order';
    private const string SUPPORT = 'support';

    protected function validate(): void
    {
        if (in_array(needle: $this->value, haystack: [self::ORDER, self::SUPPORT]) === false) {
            throw new InvalidArgumentException('Unknown chat correlation type');
        }
    }

    public static function order(): self
    {
        return new self(self::ORDER);
    }

    public static function support(): self
    {
        return new self(self::SUPPORT);
    }
}
