<?php

declare(strict_types=1);

namespace App\Shared\Application;

interface LoggerInterface
{
    public function error(string $message, array $context): void;
}
