<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Repository\Read;

use App\Catalog\Application\ReadModel\ProductImageReadModel;
use App\Catalog\Application\ReadModel\ProductReadModel;
use App\Catalog\Application\ReadModel\ProductReadModelList;
use App\Catalog\Application\ReadModel\ProductSellerAvatarReadModel;
use App\Catalog\Application\ReadModel\ProductSellerReadModel;
use App\Catalog\Application\Repository\ProductRepositoryInterface;
use App\Catalog\Domain\Entity\ProductId;
use App\Catalog\Domain\Exception\ProductNotFound;
use Doctrine\DBAL\Connection;

final readonly class DoctrineProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        private Connection $connection,
    ) {}

    public function getList(): ProductReadModelList
    {
        $qb = $this->connection->createQueryBuilder();

        $rows = $qb
            ->select(
                'p.id',
                'p.title',
                'p.description',
                'p.price',
                'pp.id as seller_id',
                'pp.name as seller_name',
                'ppi.filename as seller_avatar_filename',
                'ppi.path as seller_avatar_path',
                'i.id as image_id',
                'i.filename as image_filename',
                'i.path as image_path',
                'p.created_at',
                'p.updated_at',
            )
            ->from(table: 'products', alias: 'p')
            ->leftJoin(fromAlias: 'p', join: 'users', alias: 'u', condition: 'p.seller_id = u.id')
            ->leftJoin(fromAlias: 'u', join: 'profiles', alias: 'pp', condition: 'u.id = pp.user_id')
            ->leftJoin(fromAlias: 'pp', join: 'images', alias: 'ppi', condition: 'pp.avatar_id = ppi.id')
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
                    'seller_id' => $row['seller_id'],
                    'seller_name' => $row['seller_name'],
                    'seller_avatar_filename' => $row['seller_avatar_filename'],
                    'seller_avatar_path' => $row['seller_avatar_path'],
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

        return new ProductReadModelList(
            products: array_map(
                callback: static fn(array $data) => new ProductReadModel(
                    id: $data['id'],
                    title: $data['title'],
                    description: $data['description'],
                    price: $data['price'],
                    seller: new ProductSellerReadModel(
                        id: $data['seller_id'],
                        name: $data['seller_name'],
                        avatar: $data['seller_avatar_filename'] && $data['seller_avatar_path']
                            ? new ProductSellerAvatarReadModel(
                                filename: $data['seller_avatar_filename'],
                                path: $data['seller_avatar_path'],
                            )
                            : null,
                    ),
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

    public function getById(ProductId $id): ProductReadModel
    {
        $qb = $this->connection->createQueryBuilder();

        $rows = $qb
            ->select(
                'p.id',
                'p.title',
                'p.description',
                'p.price',
                'pp.id as seller_id',
                'pp.name as seller_name',
                'ppi.filename as seller_avatar_filename',
                'ppi.path as seller_avatar_path',
                'i.filename as image_filename',
                'i.path as image_path',
                'p.created_at',
                'p.updated_at',
            )
            ->from(table: 'products', alias: 'p')
            ->leftJoin(fromAlias: 'p', join: 'profiles', alias: 'pp', condition: 'p.seller_id = pp.id')
            ->leftJoin(fromAlias: 'pp', join: 'images', alias: 'ppi', condition: 'pp.avatar_id = ppi.id')
            ->leftJoin(fromAlias: 'p', join: 'product_images', alias: 'pi', condition: 'p.id = pi.product_id')
            ->leftJoin(fromAlias: 'pi', join: 'images', alias: 'i', condition: 'pi.image_id = i.id')
            ->where('p.id = :id')
            ->setParameter('id', $id->value())
            ->setMaxResults(1)
            ->executeQuery()
            ->fetchAllAssociative();

        if (count($rows) === 0) {
            throw ProductNotFound::byId($id->value());
        }

        $firstRow = $rows[0];
        $images = [];

        foreach ($rows as $row) {
            if ($row['image_filename'] !== null) {
                $images[] = new ProductImageReadModel(
                    filename: $row['image_filename'],
                    path: $row['image_path'],
                );
            }
        }

        $seller = new ProductSellerReadModel(
            id: $firstRow['seller_id'],
            name: $firstRow['seller_name'],
            avatar: new ProductSellerAvatarReadModel(
                filename: $firstRow['seller_avatar_filename'],
                path: $firstRow['seller_avatar_path'],
            ),
        );

        return new ProductReadModel(
            id: $firstRow['id'],
            title: $firstRow['title'],
            description: $firstRow['description'],
            price: (int) $firstRow['price'],
            seller: $seller,
            images: $images,
        );
    }
}
