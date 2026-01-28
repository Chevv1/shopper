<?php

declare(strict_types=1);

namespace App\Chat\Presentation\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class SendMessageRequest
{
    public function __construct(
        #[Assert\NotBlank]
        public string $message,
    ) {}
}
