<?php

declare(strict_types=1);

namespace App\Order\Application\Query\GetCustomerOrders;

use App\Order\Application\ReadModel\OrderReadModelList;
use App\Order\Application\Repository\OrderRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

final readonly class GetCustomerOrdersCommandHandler implements QueryHandlerInterface
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
    ) {}

    public function __invoke(GetCustomerOrdersCommand $query): OrderReadModelList
    {
        return $this->orderRepository->getByCustomerId(
            customerId: $query->customerId,
        );
    }
}
