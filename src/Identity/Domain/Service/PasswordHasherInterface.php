<?php

declare(strict_types=1);

namespace App\Identity\Domain\Service;

use App\Identity\Domain\Entity\User\HashedPassword;
use App\Identity\Domain\Entity\User\PlainPassword;

interface PasswordHasherInterface
{
    public function hash(PlainPassword $plainPassword): HashedPassword;
    public function verify(PlainPassword $plainPassword, HashedPassword $hashedPassword): bool;
}
