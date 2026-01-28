<?php

declare(strict_types=1);

namespace App\Catalog\Application\ReadModel;

use App\Shared\Application\Query\ReadModelInterface;

final readonly class ProductSellerAvatarReadModel implements ReadModelInterface
{
    public function __construct(
        public string $filename,
        public string $path,
    ) {}

    public function toArray(): array
    {
        return [
            'filename' => $this->filename,
            'url' => '/uploads/' . $this->path . '/' . $this->filename,
        ];
    }
}
