<?php

declare(strict_types=1);

namespace App\Chat\Domain\Entity;

use App\Shared\Domain\ValueObject\StringValue;

final readonly class ChatMemberRole extends StringValue
{
    private const string SELLER = 'seller';
    private const string CUSTOMER = 'customer';

    public static function seller(): self
    {
        return new self(self::SELLER);
    }

    public static function customer(): self
    {
        return new self(self::CUSTOMER);
    }

    protected function validate(): void
    {
    }
}
