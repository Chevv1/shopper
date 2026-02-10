<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Security;

use Attribute;

#[Attribute(flags: Attribute::TARGET_METHOD)]
final class IsSignedWebhook
{
}
