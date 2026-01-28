<?php

declare(strict_types=1);

namespace App\VendorManagement\Infrastructure\Repository\Write;

use App\VendorManagement\Domain\Entity\Product\ProductId;
use App\VendorManagement\Domain\Entity\Product\ProductImageIds;
use App\VendorManagement\Domain\Entity\Product\ProductImageId;
use Doctrine\DBAL\ArrayParameterType;
use Doctrine\DBAL\Connection;

final readonly class DoctrineProductImageRepository
{
    public function __construct(
        private Connection $connection,
    ) {}

    public function getIdsForProduct(ProductId $productId): ProductImageIds
    {
        $ids = $this->connection->createQueryBuilder()
            ->select('image_id')
            ->from('product_images')
            ->where('product_id = :product_id')
            ->setParameter('product_id', $productId->value())
            ->executeQuery()
            ->fetchFirstColumn();

        return new ProductImageIds($ids);
    }

    public function insert(ProductId $productId, ProductImageIds $imageIds): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder->insert('product_images');

        /** @var ProductImageId $imageId */
        foreach ($imageIds->value() as $imageId) {
            $queryBuilder
                ->values([
                    'product_id' => ':product_id',
                    'image_id'  => ':image_id',
                ])
                ->setParameter('product_id', $productId->value())
                ->setParameter('image_id', $imageId->value());

            $queryBuilder->executeStatement();
        }
    }

    public function deleteAll(ProductId $productId): void
    {
        $this->connection->delete(table: 'product_images', criteria: ['product_id' => $productId->value()]);
    }

    public function delete(ProductId $productId, ProductImageIds $imageIds): void
    {
        $queryBuilder = $this->connection->createQueryBuilder();

        $queryBuilder
            ->delete('product_images')
            ->where('product_id = :product_id')
            ->andWhere($queryBuilder->expr()->notIn('image_id', ':image_ids'))
            ->setParameter(key: 'product_id', value: $productId->value())
            ->setParameter(key: 'image_ids', value: $imageIds->value(), type: ArrayParameterType::STRING);

        $queryBuilder->executeStatement();
    }
}
