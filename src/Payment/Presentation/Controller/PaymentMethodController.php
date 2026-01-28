<?php

declare(strict_types=1);

namespace App\Payment\Presentation\Controller;

use App\Payment\Application\Query\GetActivePaymentMethods\GetActivePaymentMethodsQuery;
use App\Payment\Application\ReadModel\PaymentMethodReadModelList;
use App\Shared\Application\Bus\QueryBusInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;

final class PaymentMethodController extends AbstractController
{
    public function __construct(
        private readonly QueryBusInterface $queryBus,
    ) {}

    public function listPaymentMethods(): JsonResponse
    {
        $query = new GetActivePaymentMethodsQuery();

        /** @var PaymentMethodReadModelList $paymentMethods */
        $paymentMethods = $this->queryBus->ask($query);

        return $this->json(
            data: $paymentMethods->toArray(),
        );
    }
}
