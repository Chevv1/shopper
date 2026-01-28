<?php

declare(strict_types=1);

namespace App\Identity\Application\Command\RegisterUserIfNotExists;

use App\Identity\Domain\Entity\User\PlainPassword;
use App\Identity\Domain\Entity\User\UserEmail;
use App\Identity\Domain\Exception\InvalidCredentialsException;
use App\Identity\Domain\Exception\UserNotFound;
use App\Identity\Domain\Factory\UserFactory;
use App\Identity\Domain\Repository\UserRepositoryInterface;
use App\Identity\Domain\Service\PasswordHasherInterface;
use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\Command\CommandResult;

final readonly class RegisterUserIfNotExistsCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private UserRepositoryInterface $userRepository,
        private PasswordHasherInterface $passwordHasher,
    ) {}

    public function __invoke(RegisterUserIfNotExistsCommand $command): CommandResult
    {
        $userEmail = new UserEmail($command->email);

        try {
            $user = $this->userRepository->findByEmail($userEmail);

            if (!$this->passwordHasher->verify(
                plainPassword: new PlainPassword($command->password),
                hashedPassword: $user->password(),
            )) {
                throw new InvalidCredentialsException();
            }
        } catch (UserNotFound) {
            $user = UserFactory::create(
                email: $userEmail,
                password: $this->passwordHasher->hash(new PlainPassword($command->password)),
            );

            $this->userRepository->save($user);
        }

        return CommandResult::success(entityId: $user->id());
    }
}
