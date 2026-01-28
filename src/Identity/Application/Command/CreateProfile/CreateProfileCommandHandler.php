<?php

declare(strict_types=1);

namespace App\Identity\Application\Command\CreateProfile;

use App\Identity\Domain\Entity\Profile\Profile;
use App\Identity\Domain\Entity\Profile\ProfileAvatar;
use App\Identity\Domain\Entity\Profile\ProfileName;
use App\Identity\Domain\Entity\User\UserId;
use App\Identity\Domain\Factory\ProfileFactory;
use App\Identity\Domain\Repository\ProfileRepositoryInterface;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\Command\CommandResult;

final readonly class CreateProfileCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ProfileRepositoryInterface $profileRepository,
    ) {}

    public function __invoke(CreateProfileCommand $command): CommandResult
    {
        $avatarId = null;
        if ($command->avatarId !== null) {
            $avatarId = new ProfileAvatar($command->avatarId);
        }

        $profile = ProfileFactory::create(
            userId: new UserId($command->userId),
            name: new ProfileName($command->name),
            avatarId: $avatarId,
        );

        $this->profileRepository->save($profile);

        return CommandResult::success(entityId: $profile->userId());
    }
}
