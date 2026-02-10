<?php

declare(strict_types=1);

namespace App\Shared\Infrastructure;

use App\Shared\Application\LoggerInterface;
use Psr\Log\LoggerInterface as PsrLoggerInterface;

final readonly class PsrLogger implements LoggerInterface
{
    public function __construct(
        private PsrLoggerInterface $logger
    ) {}

    public function error(string $message, array $context): void
    {
        $this->logger->error($message, $context);
    }
}
