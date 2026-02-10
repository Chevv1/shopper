<?php

declare(strict_types=1);

namespace App\Order\Domain\Entity;

use App\Shared\Domain\ValueObject\DateTimeValue;

final readonly class OrderCreatedAt extends DateTimeValue
{
}
