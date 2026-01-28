<?php

declare(strict_types=1);

namespace App\VendorManagement\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class CannotPublishWithoutUnitsException extends DomainException
{
}
