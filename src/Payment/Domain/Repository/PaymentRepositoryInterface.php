<?php

declare(strict_types=1);

namespace App\Payment\Domain\Repository;

use App\Payment\Domain\Entity\Payment;
use App\Payment\Domain\Entity\PaymentId;
use App\Payment\Domain\Exception\PaymentNotFoundException;

interface PaymentRepositoryInterface
{
    /**
     * @throws PaymentNotFoundException
     */
    public function findById(PaymentId $id): Payment;

    public function save(Payment $payment): void;
}
