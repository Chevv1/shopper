<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Gateway;

use App\Payment\Domain\Entity\Payment;
use App\Payment\Domain\Repository\PaymentMethodRepositoryInterface;
use InvalidArgumentException;

final readonly class PaymentMethodResolver
{
    public function __construct(
        private array $methodToProviderMap,
        private PaymentMethodRepositoryInterface $paymentMethodRepository,
    ) {}

    public function resolveProvider(Payment $payment): string
    {
        $paymentMethod = $this->paymentMethodRepository->getById($payment->methodId());

        $paymentMethodType = $paymentMethod->type()->value();

        return $this->methodToProviderMap[$paymentMethodType] ?? throw new InvalidArgumentException(
            message: "Unknown payment method: {$paymentMethodType}"
        );
    }
}
