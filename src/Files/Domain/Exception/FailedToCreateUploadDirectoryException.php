<?php

declare(strict_types=1);

namespace App\Files\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class FailedToCreateUploadDirectoryException extends DomainException
{
    public function __construct()
    {
        parent::__construct(message: 'Failed to create upload directory');
    }
}
