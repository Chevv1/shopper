<?php

declare(strict_types=1);

namespace App\VendorManagement\Application\ReadModel;

use App\Shared\Application\Query\ReadModelInterface;

final readonly class ProductReadModel implements ReadModelInterface
{
    /**
     * @param ProductImageReadModel[] $images
     */
    public function __construct(
        public string $id,
        public string $title,
        public string $description,
        public int    $price,
        public array  $images,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'images' => array_map(
                callback: static fn(ProductImageReadModel $img): array => $img->toArray(),
                array: $this->images,
            ),
        ];
    }
}
