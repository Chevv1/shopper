<?php

declare(strict_types=1);

namespace App\Payment\Application\ReadModel;

use App\Shared\Application\Query\ReadModelInterface;

final readonly class PaymentMethodReadModel implements ReadModelInterface
{
    public function __construct(
        public string                     $id,
        public string                     $name,
        public string                     $type,
        public PaymentMethodLogoReadModel $logo,
    ) {}

    public function toArray(): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'type' => $this->type,
            'logo' => $this->logo->toArray(),
        ];
    }
}
