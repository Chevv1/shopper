<?php

declare(strict_types=1);

namespace App\Identity\Domain\Entity\User;

use App\Shared\Domain\ValueObject\ArrayValue;

final readonly class Roles extends ArrayValue
{
    private const string ROLE_USER = 'ROLE_USER';
    private const string ROLE_SELLER = 'ROLE_SELLER';
    private const string ROLE_CUSTOMER = 'ROLE_CUSTOMER';

    public static function seller(): self
    {
        return new self([
            self::ROLE_USER,
            self::ROLE_SELLER,
        ]);
    }

    public static function customer(): self
    {
        return new self([
            self::ROLE_USER,
            self::ROLE_CUSTOMER,
        ]);
    }

    public function isSeller(): bool
    {
        return $this->isHasRole(self::ROLE_SELLER);
    }

    public function isCustomer(): bool
    {
        return $this->isHasRole(self::ROLE_CUSTOMER);
    }

    private function isHasRole(string $role): bool
    {
        return in_array(needle: $role, haystack: $this->value());
    }
}
