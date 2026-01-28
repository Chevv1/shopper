<?php

declare(strict_types=1);

namespace App\Shared\Application\Query;

interface ReadModelInterface
{
    public function toArray(): array;
}
