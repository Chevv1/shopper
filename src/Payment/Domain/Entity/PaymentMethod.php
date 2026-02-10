<?php

declare(strict_types=1);

namespace App\Payment\Domain\Entity;

final readonly class PaymentMethod
{
    public function __construct(
        private PaymentMethodId   $id,
        private PaymentMethodName $name,
        private PaymentMethodType $type,
        private bool              $isActive,
    ) {}

    public function id(): PaymentMethodId
    {
        return $this->id;
    }

    public function name(): PaymentMethodName
    {
        return $this->name;
    }

    public function type(): PaymentMethodType
    {
        return $this->type;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }
}
