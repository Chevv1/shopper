<?php

declare(strict_types=1);

namespace App\VendorManagement\Infrastructure\Repository\Write;

use App\VendorManagement\Domain\Entity\Product\ProductId;
use App\VendorManagement\Domain\Entity\Product\ProductUnit\ProductUnit;
use App\VendorManagement\Domain\Entity\Product\ProductUnit\ProductUnitId;
use App\VendorManagement\Domain\Entity\Product\ProductUnits;
use Doctrine\DBAL\Connection;

final readonly class DoctrineProductUnitRepository
{
    public function __construct(
        private Connection $connection,
    ) {}

    public function isExists(ProductUnitId $unitId): bool
    {
        return $this->connection->fetchOne(
            query: 'SELECT COUNT(*) FROM product_units WHERE id = ?',
            params: [$unitId->value()],
        ) > 0;
    }

    public function insert(ProductId $productId, ProductUnit $unit): void
    {
        $this->connection->insert(
            table: 'product_units',
            data:[
                'id' => $unit->id()->value(),
                'product_id' => $productId->value(),
                'content' => $unit->content()->value(),
                'status' => $unit->status()->value(),
                'created_at' => $unit->createdAt()->toDateTimeString(),
                'updated_at' => $unit->updatedAt()->toDateTimeString(),
            ],
        );
    }

    public function update(ProductId $productId, ProductUnit $unit): void
    {
        $this->connection->update(
            table: 'product_units',
            data: [
                'content' => $unit->content()->value(),
                'status' => $unit->status()->value(),
                'created_at' => $unit->createdAt()->toDateTimeString(),
                'updated_at' => $unit->updatedAt()->toDateTimeString(),
            ],
            criteria: [
                'id' => $unit->id()->value(),
                'product_id' => $productId->value(),
            ],
        );
    }

    public function deleteMissing(ProductId $productId, ProductUnits $units): void
    {
        $existingIds = $units->map(
            static fn(ProductUnit $unit): string => $unit->id()->value(),
        );

        $placeholders = implode(
            separator: ',',
            array: array_fill(
                start_index: 0,
                count: count($existingIds),
                value: '?',
            ),
        );

        $this->connection->executeStatement(
            sql: "
                DELETE FROM product_units 
                WHERE product_id = ? 
                AND id NOT IN ($placeholders)
            ",
            params: array_merge([$productId], $existingIds),
        );
    }

    public function delete(ProductId $productId): void
    {
        $this->connection->delete(table: 'product_units', criteria: ['product_id' => $productId->value()]);
    }
}
