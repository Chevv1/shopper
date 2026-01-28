<?php

declare(strict_types=1);

namespace App\Chat\Domain\Entity;

final readonly class ChatCorrelation
{
    public function __construct(
        private ChatCorrelationId $id,
        private ChatCorrelationType $type,
    ) {}
    
    public function id(): ChatCorrelationId
    {
        return $this->id;
    }

    public function type(): ChatCorrelationType
    {
        return $this->type;
    }
}
