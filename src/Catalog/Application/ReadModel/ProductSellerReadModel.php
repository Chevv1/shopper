<?php

declare(strict_types=1);

namespace App\Catalog\Application\ReadModel;

use App\Shared\Application\Query\ReadModelInterface;

final readonly class ProductSellerReadModel implements ReadModelInterface
{
    public function __construct(
        public string                       $id,
        public string                       $name,
        public ?ProductSellerAvatarReadModel $avatar,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'avatar' => $this->avatar?->toArray(),
        ];
    }
}
