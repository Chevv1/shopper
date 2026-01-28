<?php

declare(strict_types=1);

namespace App\VendorManagement\Domain\Entity\Product;

use App\Shared\Domain\ValueObject\StringValue;

final readonly class ProductStatus extends StringValue
{
    private const string STATUS_CODE_DRAFT = 'draft';
    private const string STATUS_CODE_PENDING_REVIEW = 'pendingReview';
    private const string STATUS_CODE_PUBLISHED = 'published';
    private const string STATUS_CODE_ARCHIVED = 'archived';

    private const array STATUS_CODES = [
        self::STATUS_CODE_DRAFT,
        self::STATUS_CODE_PENDING_REVIEW,
        self::STATUS_CODE_PUBLISHED,
        self::STATUS_CODE_ARCHIVED,
    ];

    protected function validate(): void
    {
        if (!in_array(needle: $this->value, haystack: self::STATUS_CODES)) {
            throw new \InvalidArgumentException('Invalid product status code');
        }
    }

    public static function draft(): self
    {
        return new self(self::STATUS_CODE_DRAFT);
    }

    public static function published(): self
    {
        return new self(self::STATUS_CODE_PUBLISHED);
    }

    public static function archived(): self
    {
        return new self(self::STATUS_CODE_ARCHIVED);
    }
}
