<?php

declare(strict_types=1);

namespace App\Payment\Domain\Entity;

use App\Shared\Domain\ValueObject\StringValue;

final readonly class PaymentStatus extends StringValue
{
    private const string PENDING = 'pending';
    private const string PROCESSING = 'processing';
    private const string SUCCESS = 'success';
    private const string FAILED = 'failed';
    private const string CANCELLED = 'cancelled';
    private const string REFUNDED = 'refunded';

    public function isPending(): bool
    {
        return $this->equals(new PaymentStatus(self::PENDING));
    }

    protected function validate(): void
    {
    }

    public static function pending(): self
    {
        return new self(self::PENDING);
    }

    public static function success(): self
    {
        return new self(self::SUCCESS);
    }

    public static function failed(): self
    {
        return new self(self::FAILED);
    }

    public static function cancelled(): self
    {
        return new self(self::CANCELLED);
    }
}
