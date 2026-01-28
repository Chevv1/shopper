<?php

declare(strict_types=1);

namespace App\Identity\Domain\Exception;

use App\Identity\Domain\Entity\User\UserEmail;

final class UserNotFound extends \RuntimeException
{
    public function __construct(UserEmail $email)
    {
        parent::__construct("User with email {$email->value()} not found");
    }
}
