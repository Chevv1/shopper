<?php

declare(strict_types=1);

namespace App\VendorManagement\Application\Command\CreateProductUnit;

use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\Command\CommandResult;
use App\VendorManagement\Domain\Entity\Product\ProductId;
use App\VendorManagement\Domain\Entity\Product\ProductUnit\ProductUnit;
use App\VendorManagement\Domain\Entity\Product\ProductUnit\ProductUnitAssetIds;
use App\VendorManagement\Domain\Entity\Product\ProductUnit\ProductUnitContent;
use App\VendorManagement\Domain\Factory\ProductUnitFactory;
use App\VendorManagement\Domain\Repository\ProductRepositoryInterface;

final readonly class CreateProductUnitCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
    ) {}

    public function __invoke(CreateProductUnitCommand $command): CommandResult
    {
        $product = $this->productRepository->getById(new ProductId($command->productId));

        $productUnit = ProductUnitFactory::create(
            content: new ProductUnitContent($command->content),
            assetIds: new ProductUnitAssetIds($command->assetIds),
        );

        $product->addUnit($productUnit);

        $this->productRepository->save($product);

        return CommandResult::success(entityId: $productUnit->id());
    }
}
