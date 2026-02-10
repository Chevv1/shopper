<?php

declare(strict_types=1);

namespace App\Order\Application\Query\GetCustomerOrder;

use App\Order\Application\ReadModel\OrderReadModel;
use App\Order\Application\Repository\OrderRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

final readonly class GetCustomerOrderQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private OrderRepositoryInterface $orderRepository,
    ) {}

    public function __invoke(GetCustomerOrderQuery $query): OrderReadModel
    {
        return $this->orderRepository->getByIdAndCustomerId(
            id: $query->orderId,
            customerId: $query->customerId,
        );
    }
}
