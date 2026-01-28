<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

use InvalidArgumentException;

abstract readonly class FileValue extends ValueObject
{
    public function __construct(
        private string $filename,
        private string $path,
        private string $mimeType,
        private int $size,
    ) {
        if (empty($filename)) {
            throw new InvalidArgumentException('Filename cannot be empty');
        }

        if (empty($path)) {
            throw new InvalidArgumentException('Path cannot be empty');
        }

        if ($size <= 0) {
            throw new InvalidArgumentException('File size must be positive');
        }
    }

    final public function filename(): string
    {
        return $this->filename;
    }

    final public function path(): string
    {
        return $this->path;
    }

    final public function mimeType(): string
    {
        return $this->mimeType;
    }

    final public function size(): int
    {
        return $this->size;
    }

    final public function fullPath(): string
    {
        return $this->path . '/' . $this->filename;
    }
}
