<?php

declare(strict_types=1);

namespace App\Cart\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class CartNotFoundException extends DomainException
{
}
