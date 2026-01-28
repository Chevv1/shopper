<?php

declare(strict_types=1);

namespace App\Identity\Domain\Event;

use App\Identity\Domain\Entity\Profile\ProfileId;
use App\Identity\Domain\Entity\User\UserId;
use App\Shared\Domain\Event\DomainEvent;

final readonly class ProfileCreated extends DomainEvent
{
    public function __construct(
        public ProfileId $profileId,
        public UserId    $userId,
    ) {
        parent::__construct();
    }
}
