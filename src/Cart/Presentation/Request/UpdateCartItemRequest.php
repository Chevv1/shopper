<?php

declare(strict_types=1);

namespace App\Cart\Presentation\Request;

use Symfony\Component\Validator\Constraints as Assert;

class UpdateCartItemRequest
{
    public function __construct(
        #[Assert\NotBlank]
        #[Assert\Positive]
        public int $quantity,
    ) {}
}
