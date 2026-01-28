<?php

declare(strict_types=1);

namespace App\Identity\Presentation\Request;

use Symfony\Component\Validator\Constraints as Assert;

final readonly class CreateProfileForCurrentUser
{
    public function __construct(
        #[Assert\Length(min: 5, max: 50)]
        #[Assert\NotBlank]
        public string $name,

        #[Assert\Uuid]
        public ?string $avatarId,
    ) {}
}
