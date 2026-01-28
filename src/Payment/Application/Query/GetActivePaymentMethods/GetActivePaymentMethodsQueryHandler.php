<?php

declare(strict_types=1);

namespace App\Payment\Application\Query\GetActivePaymentMethods;

use App\Payment\Application\ReadModel\PaymentMethodReadModelList;
use App\Payment\Application\Repository\PaymentMethodRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

final readonly class GetActivePaymentMethodsQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private PaymentMethodRepositoryInterface $paymentMethodRepository,
    ) {}

    public function __invoke(GetActivePaymentMethodsQuery $query): PaymentMethodReadModelList
    {
        return $this->paymentMethodRepository->findAllActive();
    }
}
