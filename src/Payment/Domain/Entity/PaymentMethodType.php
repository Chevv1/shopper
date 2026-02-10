<?php

declare(strict_types=1);

namespace App\Payment\Domain\Entity;

use App\Shared\Domain\ValueObject\StringValue;

final readonly class PaymentMethodType extends StringValue
{
    private const string CRYPTO = 'crypto';

    protected function validate(): void
    {
    }

    public static function crypto(): self
    {
        return new self(self::CRYPTO);
    }
}
