<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Gateway;

use App\Payment\Domain\Entity\Payment;
use App\Payment\Domain\Entity\PaymentStatus;
use App\Payment\Domain\Entity\PaymentUrl;
use App\Payment\Domain\Service\PaymentGatewayInterface;
use InvalidArgumentException;

final readonly class CompositePaymentGateway implements PaymentGatewayInterface
{
    /**
     * @param array<string, PaymentGatewayInterface> $gateways
     */
    public function __construct(
        private array                 $gateways,
        private PaymentMethodResolver $resolver,
    ) {}

    public function createPaymentSession(Payment $payment, string $successUrl): PaymentUrl
    {
        $gateway = $this->getGatewayForPayment($payment);

        return $gateway->createPaymentSession($payment, $successUrl);
    }

    public function getPaymentStatus(Payment $payment): PaymentStatus
    {
        return PaymentStatus::success();
    }

    public function cancelPayment(Payment $payment): void
    {
    }

    public function refundPayment(Payment $payment): void
    {
    }

    private function getGatewayForPayment(Payment $payment): PaymentGatewayInterface
    {
        $provider = $this->resolver->resolveProvider($payment);

        return $this->getGatewayByProvider($provider);
    }

    private function getGatewayByProvider(string $provider): PaymentGatewayInterface
    {
        if (!isset($this->gateways[$provider])) {
            throw new InvalidArgumentException(
                message: "Payment gateway not found for provider: {$provider}"
            );
        }

        return $this->gateways[$provider];
    }
}
