<?php

declare(strict_types=1);

namespace App\Payment\Domain\Service;

use App\Payment\Domain\Entity\Payment;
use App\Payment\Domain\Entity\PaymentStatus;
use App\Payment\Domain\Entity\PaymentUrl;

interface PaymentGatewayInterface
{
    public function createPaymentSession(Payment $payment, string $successUrl): PaymentUrl;
    public function getPaymentStatus(Payment $payment): PaymentStatus;
    public function cancelPayment(Payment $payment): void;
    public function refundPayment(Payment $payment): void;
}
