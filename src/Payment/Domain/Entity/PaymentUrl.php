<?php

declare(strict_types=1);

namespace App\Payment\Domain\Entity;

use App\Shared\Domain\ValueObject\StringValue;

final readonly class PaymentUrl extends StringValue
{
    protected function validate(): void
    {
        if (filter_var(value: $this->value, filter: FILTER_VALIDATE_URL) === false) {
            throw new \InvalidArgumentException("Invalid payment URL: {$this->value}");
        }

        if (str_starts_with(haystack: $this->value, needle: 'https://') === false) {
//            throw new \InvalidArgumentException("Payment URL must use HTTPS");
        }
    }
}
