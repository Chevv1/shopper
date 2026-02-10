<?php

declare(strict_types=1);

namespace App\Payment\Application\Command\CreatePayment;

use App\Shared\Application\Command\CommandInterface;

final readonly class CreatePaymentCommand implements CommandInterface
{
    public function __construct(
        public string $ownerId,
        public string $orderId,
        public string $paymentMethodId,
        public string $successUrl,
    ) {}
}
