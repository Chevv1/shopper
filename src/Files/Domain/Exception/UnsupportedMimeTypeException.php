<?php

declare(strict_types=1);

namespace App\Files\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class UnsupportedMimeTypeException extends DomainException
{
    public function __construct(string $mimeType)
    {
        parent::__construct(message: "Unsupported mime type: {$mimeType}");
    }
}
