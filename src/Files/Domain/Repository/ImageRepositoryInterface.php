<?php

declare(strict_types=1);

namespace App\Files\Domain\Repository;

use App\Files\Domain\Entity\Image;

interface ImageRepositoryInterface
{
    public function save(Image $image): void;
}
