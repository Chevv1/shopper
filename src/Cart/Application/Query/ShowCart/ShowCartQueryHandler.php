<?php

declare(strict_types=1);

namespace App\Cart\Application\Query\ShowCart;

use App\Cart\Application\ReadModel\CartReadModel;
use App\Cart\Application\Repository\CartRepositoryInterface;
use App\Shared\Application\Query\QueryHandlerInterface;

final readonly class ShowCartQueryHandler implements QueryHandlerInterface
{
    public function __construct(
        private CartRepositoryInterface $cartRepository,
    ) {}

    public function __invoke(ShowCartQuery $query): CartReadModel
    {
        return $this->cartRepository->getByOwnerId(ownerId: $query->ownerId);
    }
}
