<?php

declare(strict_types=1);

namespace App\Payment\Presentation\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class HandleWebhookRequest
{
    public function __construct(
        #[Assert\Uuid]
        #[Assert\NotBlank]
        public string $paymentId,

        #[Assert\Choice(choices: ['paid', 'failed'])]
        #[Assert\NotBlank]
        public string $status,
    ) {}
}
