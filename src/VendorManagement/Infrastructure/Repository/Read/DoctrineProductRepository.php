<?php

declare(strict_types=1);

namespace App\VendorManagement\Infrastructure\Repository\Read;

use App\VendorManagement\Application\ReadModel\ProductImageReadModel;
use App\VendorManagement\Application\ReadModel\ProductListReadModel;
use App\VendorManagement\Application\ReadModel\ProductReadModel;
use App\VendorManagement\Application\Repository\ProductRepositoryInterface;
use Doctrine\DBAL\Connection;

final readonly class DoctrineProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        private Connection $connection,
    ) {}

    public function getBySellerId(string $sellerId): ProductListReadModel
    {
        $qb = $this->connection->createQueryBuilder();

        $rows = $qb
            ->select(
                'p.id',
                'p.title',
                'p.description',
                'p.price',
                'i.id as image_id',
                'i.filename as image_filename',
                'i.path as image_path',
                'p.created_at',
                'p.updated_at',
            )
            ->from(table: 'products', alias: 'p')
            ->leftJoin(fromAlias: 'p', join: 'users', alias: 'u', condition: 'p.seller_id = u.id')
            ->leftJoin(fromAlias: 'p', join: 'product_images', alias: 'pi', condition: 'p.id = pi.product_id')
            ->leftJoin(fromAlias: 'pi', join: 'images', alias: 'i', condition: 'pi.image_id = i.id')
            ->orderBy(sort: 'p.created_at', order: 'desc')
            ->executeQuery()
            ->fetchAllAssociative();

        $products = [];
        foreach ($rows as $row) {
            $productId = $row['id'];

            if (array_key_exists($productId, $products) === false) {
                $products[$productId] = [
                    'id' => $row['id'],
                    'title' => $row['title'],
                    'description' => $row['description'],
                    'price' => (int)$row['price'],
                    'created_at' => $row['created_at'],
                    'updated_at' => $row['updated_at'],
                    'images' => [],
                ];
            }

            if ($row['image_id'] !== null) {
                $products[$productId]['images'][] = [
                    'filename' => $row['image_filename'],
                    'path' => $row['image_path'],
                ];
            }
        }

        return new ProductListReadModel(
            items: array_map(
                callback: static fn(array $data) => new ProductReadModel(
                    id: $data['id'],
                    title: $data['title'],
                    description: $data['description'],
                    price: $data['price'],
                    images: array_map(
                        callback: fn(array $img) => new ProductImageReadModel(
                            filename: $img['filename'],
                            path: $img['path'],
                        ),
                        array: $data['images'],
                    ),
                ),
                array: array_values($products),
            ),
        );
    }
}
