<?php

declare(strict_types=1);

namespace App\Payment\Application\Command\HandleWebhook;

use App\Shared\Application\Command\CommandInterface;

final readonly class HandleWebhookCommand implements CommandInterface
{
    public function __construct(
        public string $paymentId,
        public string $status,
    ) {}
}
