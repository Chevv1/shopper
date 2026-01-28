<?php

declare(strict_types=1);

namespace App\VendorManagement\Presentation\Controller;

use App\Shared\Application\Bus\CommandBusInterface;
use App\VendorManagement\Application\Command\CreateProduct\CreateProductCommand;
use App\VendorManagement\Application\Command\CreateProductUnit\CreateProductUnitCommand;
use App\VendorManagement\Presentation\Request\CreateProductRequest;
use App\VendorManagement\Presentation\Request\CreateProductUnitRequest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Attribute\MapRequestPayload;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

final class ProductController extends AbstractController
{
    public function __construct(
        private readonly CommandBusInterface $commandBus,
    ) {}

    public function createProduct(
        #[MapRequestPayload] CreateProductRequest $requestDTO,
    ): JsonResponse {
        $user = $this->getUser();
        if ($user === null) {
            throw new AccessDeniedHttpException('User must be authenticated');
        }

        $this->commandBus->dispatch(
            command: new CreateProductCommand(
                title: $requestDTO->title,
                description: $requestDTO->description,
                price: $requestDTO->price,
                imageIds: $requestDTO->imageIds,
                userId: $user->getUserIdentifier(),
                categoryId: $requestDTO->categoryId,
            ),
        );

        return $this->json(
            data: [
                'success' => true,
            ],
            status: Response::HTTP_CREATED,
        );
    }

    public function createProductUnit(
        #[MapRequestPayload] CreateProductUnitRequest $requestDTO,
        string $productId,
    ): JsonResponse {
        $user = $this->getUser();
        if ($user === null) {
            throw new AccessDeniedHttpException('User must be authenticated');
        }

        $this->commandBus->dispatch(
            command: new CreateProductUnitCommand(
                userId: $user->getUserIdentifier(),
                productId: $productId,
                content: $requestDTO->content,
                assetIds: $requestDTO->assetIds,
            ),
        );

        return $this->json(
            data: [
                'success' => true,
            ],
            status: Response::HTTP_CREATED,
        );
    }
}
