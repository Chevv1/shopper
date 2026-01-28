<?php

declare(strict_types=1);

namespace App\Shared\Application\Bus;

use App\Shared\Application\Query\ReadModelInterface;

interface QueryBusInterface
{
    public function ask(object $query): ReadModelInterface;
}
