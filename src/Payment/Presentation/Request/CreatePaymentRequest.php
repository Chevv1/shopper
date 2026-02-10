<?php

declare(strict_types=1);

namespace App\Payment\Presentation\Request;

use Symfony\Component\Validator\Constraints as Assert;

final class CreatePaymentRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $orderId,

        #[Assert\NotBlank]
        #[Assert\Uuid]
        public string $paymentMethodId,

        #[Assert\NotBlank]
        #[Assert\Url(requireTld: false)]
        public string $successUrl,
    ) {}
}
