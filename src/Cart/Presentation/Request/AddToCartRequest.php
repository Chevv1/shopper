<?php

declare(strict_types=1);

namespace App\Cart\Presentation\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class AddToCartRequest
{
    public function __construct(
        #[Assert\Uuid]
        #[Assert\NotBlank]
        public string $productId,

        #[Assert\NotBlank]
        #[Assert\Positive]
        public int $quantity,
    ) {}
}
