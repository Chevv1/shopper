<?php

declare(strict_types=1);

namespace App\Catalog\Domain\Exception;

use App\Shared\Domain\Exception\DomainException;

final class ProductNotFound extends DomainException
{
    public function __construct(string $message)
    {
        parent::__construct($message);
    }

    public static function byId(string $id): self
    {
        return new self("Product with id $id not found");
    }
}
