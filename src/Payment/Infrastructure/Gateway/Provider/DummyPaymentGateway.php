<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Gateway\Provider;

use App\Payment\Domain\Entity\Payment;
use App\Payment\Domain\Entity\PaymentStatus;
use App\Payment\Domain\Entity\PaymentUrl;
use App\Payment\Domain\Service\PaymentGatewayInterface;

final readonly class DummyPaymentGateway implements PaymentGatewayInterface
{
    public function __construct(
        private string $gatewayUrl,
    ) {}

    public function createPaymentSession(Payment $payment, string $successUrl): PaymentUrl
    {
        $query = http_build_query([
            'id' => $payment->id()->value(),
            'redirect' => $successUrl,
        ]);

        $url = sprintf('%s?%s', $this->gatewayUrl, $query);

        return new PaymentUrl($url);
    }

    public function getPaymentStatus(Payment $payment): PaymentStatus
    {
        return $payment->status();
    }

    public function cancelPayment(Payment $payment): void
    {
    }

    public function refundPayment(Payment $payment): void
    {
    }
}
