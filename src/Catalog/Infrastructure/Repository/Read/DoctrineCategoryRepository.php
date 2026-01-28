<?php

declare(strict_types=1);

namespace App\Catalog\Infrastructure\Repository\Read;

use App\Catalog\Application\ReadModel\CategoryReadModel;
use App\Catalog\Application\ReadModel\CategoryReadModelList;
use App\Catalog\Application\Repository\CategoryRepositoryInterface;
use Doctrine\DBAL\Connection;

final readonly class DoctrineCategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(
        private Connection $connection,
    ) {}

    public function findAll(): CategoryReadModelList
    {
        $qb = $this->connection->createQueryBuilder();

        $rows = $qb
            ->select(
                'c.id',
                'c.name',
                'c.parent_id'
            )
            ->from(table: 'categories', alias: 'c')
            ->orderBy(sort: 'c.name', order: 'ASC')
            ->orderBy(sort: 'c.parent_id', order: 'DESC')
            ->fetchAllAssociative();

        return new CategoryReadModelList(
            items: array_map(
                callback: static fn(array $row): CategoryReadModel => self::hydrate($row),
                array: self::buildTree($rows),
            ),
        );
    }

    private static function buildTree(array $rows): array
    {
        $items = [];
        $tree = [];

        // Сначала делаем удобный доступ по id
        foreach ($rows as $row) {
            $items[$row['id']] = $row;
            $items[$row['id']]['children'] = []; // сразу добавляем пустой массив
        }

        // Распределяем по родителям
        foreach ($items as $id => $item) {
            if ($item['parent_id'] === null) {
                $tree[] = &$items[$id];
            } else if (isset($items[$item['parent_id']])) {
                $items[$item['parent_id']]['children'][] = &$items[$id];
            }
        }

        return $tree;
    }

    private static function hydrate(array $row): CategoryReadModel
    {
        return new CategoryReadModel(
            id: $row['id'],
            name: $row['name'],
            children: array_map(
                callback: static fn(array $row): CategoryReadModel => self::hydrate($row),
                array: $row['children'],
            ),
            productCount: 0,
        );
    }
}
