<?php

declare(strict_types=1);

namespace App\Files\Application\Command\UploadImage;

use App\Shared\Application\Command\CommandInterface;

final readonly class UploadImageCommand implements CommandInterface
{
    public function __construct(
        public string $tmpName,
        public string $name,
        public string $type,
        public int $size,
        public string $ownerUserId,
    ) {}
}
