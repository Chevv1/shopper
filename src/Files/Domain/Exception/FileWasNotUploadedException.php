<?php

declare(strict_types=1);

namespace App\Files\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class FileWasNotUploadedException extends DomainException
{
    public static function viaHTTP(): self
    {
        return new self(message: 'File was not uploaded via HTTP POST');
    }
}
