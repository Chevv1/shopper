<?php

declare(strict_types=1);

namespace App\Shared\Domain\ValueObject;

abstract readonly class ImageValue extends FileValue
{
    public function __construct(
        string $filename,
        string $path,
        string $mimeType,
        int $size,
        private int $width,
        private int $height,
    ) {
        parent::__construct($filename, $path, $mimeType, $size);
    }

    final public function width(): int
    {
        return $this->width;
    }

    final public function height(): int
    {
        return $this->height;
    }
}
