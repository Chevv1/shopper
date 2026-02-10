<?php

declare(strict_types=1);

namespace App\Payment\Infrastructure\Repository\Write;

use App\Payment\Domain\Entity\PaymentMethod;
use App\Payment\Domain\Entity\PaymentMethodId;
use App\Payment\Domain\Entity\PaymentMethodName;
use App\Payment\Domain\Entity\PaymentMethodType;
use App\Payment\Domain\Exception\PaymentMethodNotFoundException;
use App\Payment\Domain\Repository\PaymentMethodRepositoryInterface;
use App\Shared\Infrastructure\Persistence\Hydration\ReflectionHydrator;
use Doctrine\DBAL\Connection;

final readonly class DoctrinePaymentMethodRepository implements PaymentMethodRepositoryInterface
{
    use ReflectionHydrator;

    public function __construct(
        private Connection $connection,
    ) {}

    public function getById(PaymentMethodId $id): PaymentMethod
    {
        $paymentMethodData = $this->connection->fetchAssociative(
            query: '
                SELECT
                    id,
                    name,
                    type,
                    is_active
                FROM
                    payment_methods
                WHERE
                    id = ?
            ',
            params: [
                $id->value(),
            ],
        );

        if (empty($paymentMethodData) === true) {
            throw new PaymentMethodNotFoundException();
        }

        return self::hydrate(
            className: PaymentMethod::class,
            data: [
                'id' => new PaymentMethodId($paymentMethodData['id']),
                'name' => new PaymentMethodName($paymentMethodData['name']),
                'type' => new PaymentMethodType($paymentMethodData['type']),
                'isActive' => $paymentMethodData['is_active'],
            ],
        );
    }
}
