<?php

declare(strict_types=1);

namespace App\Identity\Domain\Entity\User;

use App\Identity\Domain\Event\UserBecameCustomer;
use App\Identity\Domain\Event\UserBecameSeller;
use App\Identity\Domain\Event\UserCreated;
use App\Shared\Domain\Entity\AggregateRoot;

final class User extends AggregateRoot
{
    public function __construct(
        private readonly UserId        $id,
        private UserEmail              $email,
        private HashedPassword         $password,
        private Roles                  $roles,
        private readonly UserCreatedAt $createdAt,
        private UserUpdatedAt          $updatedAt,
    ) {
        $this->recordEvent(new UserCreated());
    }

    // Commands

    public function becomeSeller(): void
    {
        if ($this->roles->isSeller()) {
            return;
        }

        $this->roles = Roles::seller();
        $this->updatedAt = UserUpdatedAt::now();

        $this->recordEvent(new UserBecameSeller());
    }

    public function becomeCustomer(): void
    {
        if ($this->roles->isCustomer()) {
            return;
        }

        $this->roles = Roles::customer();
        $this->updatedAt = UserUpdatedAt::now();

        $this->recordEvent(new UserBecameCustomer());
    }

    // Getters

    public function id(): UserId
    {
        return $this->id;
    }

    public function email(): UserEmail
    {
        return $this->email;
    }

    public function roles(): Roles
    {
        return $this->roles;
    }

    public function password(): HashedPassword
    {
        return $this->password;
    }

    public function createdAt(): UserCreatedAt
    {
        return $this->createdAt;
    }

    public function updatedAt(): UserUpdatedAt
    {
        return $this->updatedAt;
    }
}
