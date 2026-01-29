<?php

declare(strict_types=1);

namespace App\VendorManagement\Infrastructure\Repository\Write;

use App\Shared\Infrastructure\Persistence\Hydration\ReflectionHydrator;
use App\VendorManagement\Domain\Entity\Product\Product;
use App\VendorManagement\Domain\Entity\Product\ProductCategoryId;
use App\VendorManagement\Domain\Entity\Product\ProductCreatedAt;
use App\VendorManagement\Domain\Entity\Product\ProductDescription;
use App\VendorManagement\Domain\Entity\Product\ProductId;
use App\VendorManagement\Domain\Entity\Product\ProductImageId;
use App\VendorManagement\Domain\Entity\Product\ProductImageIds;
use App\VendorManagement\Domain\Entity\Product\ProductPrice;
use App\VendorManagement\Domain\Entity\Product\ProductStatus;
use App\VendorManagement\Domain\Entity\Product\ProductTitle;
use App\VendorManagement\Domain\Entity\Product\ProductUnit\ProductUnit;
use App\VendorManagement\Domain\Entity\Product\ProductUnit\ProductUnitId;
use App\VendorManagement\Domain\Entity\Product\ProductUnit\ProductUpdatedAt;
use App\VendorManagement\Domain\Entity\Product\ProductUnits;
use App\VendorManagement\Domain\Entity\Seller\SellerId;
use App\VendorManagement\Domain\Exception\ProductNotFoundException;
use App\VendorManagement\Domain\Exception\UnableToSaveProductException;
use App\VendorManagement\Domain\Repository\ProductRepositoryInterface;
use Doctrine\DBAL\Connection;
use Exception;

final readonly class DoctrineProductRepository implements ProductRepositoryInterface
{
    use ReflectionHydrator;

    public function __construct(
        private Connection $connection,
        private DoctrineProductImageRepository $productImageRepository,
        private DoctrineProductUnitRepository $productUnitRepository,
    ) {}

    public function getById(ProductId $id): Product
    {
        $qb = $this->connection->createQueryBuilder();

        $rows = $qb
            ->select(
                'p.id',
                'p.title',
                'p.description',
                'p.status',
                'p.category_id',
                'p.price',
                'p.seller_id',
                'pi.image_id',
                'pi.is_available',
                'pu.id as unit_id',
                'p.created_at',
                'p.updated_at',
            )
            ->from(table: 'products', alias: 'p')
            ->leftJoin(fromAlias: 'p', join: 'product_images', alias: 'pi', condition: 'p.id = pi.product_id')
            ->leftJoin(fromAlias: 'p', join: 'product_units', alias: 'pu', condition: 'p.id = pu.product_id')
            ->where('p.id = :id')
            ->setParameter('id', $id->value())
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchAllAssociative();

        if (count($rows) === 0) {
            throw ProductNotFoundException::byId($id->value());
        }

        $firstRow = $rows[0];
        $images = $units = [];

        foreach ($rows as $row) {
            $productImageId = $row['image_id'];
            $productUnitId = $row['unit_id'];

            if ($productImageId !== null) {
                $images[$productImageId] = new ProductImageId($productImageId);
            }

            if ($productUnitId !== null) {
                $units[$productUnitId] = new ProductUnitId($productUnitId);
            }
        }

        return self::hydrate(
            className: Product::class,
            data: [
                'id' => new ProductId($firstRow['id']),
                'sellerId' => new SellerId($firstRow['seller_id']),
                'title' => new ProductTitle($firstRow['title']),
                'description' => new ProductDescription($firstRow['description']),
                'categoryId' => new ProductCategoryId($firstRow['category_id']),
                'price' => new ProductPrice($firstRow['price']),
                'imageIds' => new ProductImageIds($images),
                'status' => new ProductStatus($firstRow['status']),
                'isAvailable' => (bool) $firstRow['is_available'],
                'units' => new ProductUnits($units),
                'createdAt' => ProductCreatedAt::fromString($firstRow['created_at']),
                'updatedAt' => ProductUpdatedAt::fromString($firstRow['updated_at']),
            ],
        );
    }

    public function save(Product $product): void
    {
        $this->connection->beginTransaction();

        try {
            $productId = $product->id()->value();

            $data = [
                'id' => $productId,
                'title' => $product->title()->value(),
                'description' => $product->description()->value(),
                'price' => $product->price()->value(),
                'seller_id' => $product->sellerId()->value(),
                'is_available' => $product->isAvailable(),
                'created_at' => $product->createdAt()->toDateTimeString(),
                'updated_at' => $product->updatedAt()->toDateTimeString(),
            ];

            if ($this->isExists(id: $productId)) {
                $this->update(id: $productId, data: $data);
            } else {
                $this->insert(data: $data);
            }

            $this->syncImages(productId: $product->id(), imageIds: $product->imageIds());
            $this->syncUnits(productId: $product->id(), units: $product->units());

            $this->connection->commit();
        } catch (Exception $e) {
            $this->connection->rollBack();

            throw new UnableToSaveProductException($e->getMessage());
        }
    }

    private function isExists(string $id): bool
    {
        return $this->connection->fetchOne(
            query: '
                SELECT COUNT(*)
                FROM products
                WHERE id = :id
            ',
            params: ['id' => $id],
        ) === 1;
    }

    private function insert(array $data): void
    {
        $this->connection->insert(table: 'products', data: $data);
    }

    private function update(string $id, array $data): void
    {
        $this->connection->update(table: 'products', data: $data, criteria: ['id' => $id]);
    }

    private function syncImages(ProductId $productId, ProductImageIds $imageIds): void
    {
        $imageIds = $imageIds->unique();

        if ($imageIds->isEmpty()) {
            $this->productImageRepository->deleteAll(productId: $productId);
            return;
        }

        $this->connection->beginTransaction();

        try {
            $this->productImageRepository->delete(productId: $productId, imageIds: $imageIds);

            $currentImageIds = $this->productImageRepository->getIdsForProduct(productId: $productId);
            $toInsertImageIds = $imageIds->diff($currentImageIds);

            if ($toInsertImageIds->isEmpty() === false) {
                $this->productImageRepository->insert(productId: $productId, imageIds: $toInsertImageIds);
            }

            $this->connection->commit();
        } catch (Exception $e) {
            $this->connection->rollBack();

            throw $e;
        }
    }

    private function syncUnits(ProductId $productId, ProductUnits $units): void
    {
        if ($units->isEmpty() === true) {
            $this->productUnitRepository->delete(productId: $productId);

            return;
        }

        $this->productUnitRepository->deleteMissing(productId: $productId, units: $units);

        /** @var ProductUnit $unit */
        foreach ($units as $unit) {
            if ($this->productUnitRepository->isExists($unit->id())) {
                $this->productUnitRepository->update(productId: $productId, unit: $unit);
            } else {
                $this->productUnitRepository->insert(productId: $productId, unit: $unit);
            }
        }
    }
}
