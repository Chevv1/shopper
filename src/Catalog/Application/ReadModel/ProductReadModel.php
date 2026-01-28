<?php

declare(strict_types=1);

namespace App\Catalog\Application\ReadModel;

use App\Shared\Application\Query\ReadModelInterface;

final readonly class ProductReadModel implements ReadModelInterface
{
    /**
     * @param ProductImageReadModel[] $images
     */
    public function __construct(
        public string                 $id,
        public string                 $title,
        public string                 $description,
        public int                    $price,
        public ProductSellerReadModel $seller,
        public array                  $images,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'price' => $this->price,
            'seller' => $this->seller->toArray(),
            'images' => array_map(
                callback: static fn(ProductImageReadModel $img) => $img->toArray(),
                array: $this->images,
            ),
        ];
    }
}
