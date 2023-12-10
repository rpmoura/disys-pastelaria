<?php

namespace App\Services\Product;

use App\DTO\FileUpload;
use App\Models\Product;
use App\Repositories\Product\ProductRepositoryInterface;
use App\Services\FileManager\FileManagerServiceInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ProductService implements ProductServiceInterface
{
    public function __construct(
        private readonly ProductRepositoryInterface $repository,
        private readonly FileManagerServiceInterface $fileManagerService
    ) {
    }

    public function create(array $attributes): Product
    {
        $fileUpload          = new FileUpload(directory: 'products', content: $attributes['photo']);
        $attributes['photo'] = $this->fileManagerService->upload($fileUpload);

        return $this->repository->create($attributes);
    }

    public function update(Product $product, array $attributes): Product
    {
        if (!empty($attributes['photo']) && !filter_var($attributes['photo'], FILTER_VALIDATE_URL)) {
            $fileUpload          = new FileUpload(directory: 'products', content: $attributes['photo']);
            $attributes['photo'] = $this->fileManagerService->upload($fileUpload);
        }

        return $this->repository->update($attributes, $product->id);
    }

    public function findOneBy(string $key, int|string $value): Product
    {
        $product = $this->repository->findBy($key, $value)->first();

        if (!$product instanceof Product) {
            throw new NotFoundHttpException(__('exception.product.not_found'));
        }

        return $product;
    }

    public function findList(): ProductRepositoryInterface
    {
        return $this->repository;
    }

    public function delete(Product $product): bool
    {
        return $this->repository->delete($product->id);
    }
}
