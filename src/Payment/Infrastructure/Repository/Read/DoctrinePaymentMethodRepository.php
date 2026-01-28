<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Repository\Read;

use App\Payment\Application\ReadModel\PaymentMethodLogoReadModel;
use App\Payment\Application\ReadModel\PaymentMethodReadModel;
use App\Payment\Application\ReadModel\PaymentMethodReadModelList;
use App\Payment\Application\Repository\PaymentMethodRepositoryInterface;
use Doctrine\DBAL\Connection;

final readonly class DoctrinePaymentMethodRepository implements PaymentMethodRepositoryInterface
{
    public function __construct(
        private Connection $connection,
    ) {}

    public function findAllActive(): PaymentMethodReadModelList
    {
        $qb = $this->connection->createQueryBuilder();

        $rows = $qb
            ->select(
                'p.id',
                'p.name',
                'p.type',
                'pi.filename as logo_filename',
                'pi.path as logo_path',
            )
            ->from(table: 'payment_methods', alias: 'p')
            ->leftJoin(fromAlias: 'p', join: 'images', alias: 'pi', condition: 'p.logo_id = pi.id')
            ->where(predicate: 'p.is_active = true')
            ->orderBy(sort: 'p.name', order: 'ASC')
            ->fetchAllAssociative();

        return new PaymentMethodReadModelList(
            items: array_map(
                callback: static fn(array $row) => new PaymentMethodReadModel(
                    id: $row['id'],
                    name: $row['name'],
                    type: $row['type'],
                    logo: new PaymentMethodLogoReadModel(
                        filename: $row['logo_filename'],
                        path: $row['logo_path'],
                    ),
                ),
                array: $rows,
            ),
        );
    }
}
