<?php

declare(strict_types=1);

namespace App\Payment\Domain\Repository;

use App\Payment\Domain\Entity\PaymentMethod;
use App\Payment\Domain\Entity\PaymentMethodId;
use App\Payment\Domain\Exception\PaymentMethodNotFoundException;

interface PaymentMethodRepositoryInterface
{
    /**
     * @throws PaymentMethodNotFoundException
     */
    public function getById(PaymentMethodId $id): PaymentMethod;
}
