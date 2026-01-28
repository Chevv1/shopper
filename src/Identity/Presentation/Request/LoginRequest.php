<?php

declare(strict_types=1);

namespace App\Identity\Presentation\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class LoginRequest
{
    public function __construct(
        #[Assert\Email]
        #[Assert\NotBlank]
        public string $email,

        #[Assert\NotBlank]
        public string $password,
    )
    {
    }
}
