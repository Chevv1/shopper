<?php

declare(strict_types=1);

namespace App\Payment\Application\Repository;

use App\Payment\Application\ReadModel\PaymentMethodReadModelList;

interface PaymentMethodRepositoryInterface
{
    public function findAllActive(): PaymentMethodReadModelList;
}
