<?php

declare(strict_types=1);

namespace App\VendorManagement\Application\Command\CreateProduct;

use App\Shared\Application\Command\CommandHandlerInterface;
use App\Shared\Application\Command\CommandResult;
use App\VendorManagement\Domain\Entity\Product\Product;
use App\VendorManagement\Domain\Entity\Product\ProductCategoryId;
use App\VendorManagement\Domain\Entity\Product\ProductDescription;
use App\VendorManagement\Domain\Entity\Product\ProductImageId;
use App\VendorManagement\Domain\Entity\Product\ProductImageIds;
use App\VendorManagement\Domain\Entity\Product\ProductPrice;
use App\VendorManagement\Domain\Entity\Product\ProductTitle;
use App\VendorManagement\Domain\Entity\Seller\SellerId;
use App\VendorManagement\Domain\Factory\ProductFactory;
use App\VendorManagement\Domain\Repository\ProductRepositoryInterface;

final readonly class CreateProductCommandHandler implements CommandHandlerInterface
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
    ) {}

    public function __invoke(CreateProductCommand $command): CommandResult
    {
        $product = ProductFactory::create(
            sellerId: new SellerId($command->userId),
            title: new ProductTitle($command->title),
            description: new ProductDescription($command->description),
            categoryId: new ProductCategoryId($command->categoryId),
            price: new ProductPrice($command->price),
            imageIds: new ProductImageIds(
                array_map(
                    callback: static fn(string $productImageId) => new ProductImageId($productImageId),
                    array: $command->imageIds,
                ),
            ),
        );

        $this->productRepository->save($product);

        return CommandResult::success(entityId: $product->id());
    }
}
