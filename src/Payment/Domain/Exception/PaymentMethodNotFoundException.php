<?php

declare(strict_types=1);

namespace App\Payment\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class PaymentMethodNotFoundException extends DomainException
{
}
