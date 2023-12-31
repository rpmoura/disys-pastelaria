<?php

namespace App\Services\Product;

use App\Models\Product;
use App\Repositories\Product\ProductRepositoryInterface;
use Illuminate\Support\Collection;

interface ProductServiceInterface
{
    public function create(array $attributes): Product;

    public function update(Product $product, array $attributes): Product;

    public function findOneBy(string $key, int|string $value): Product;

    public function findList(): ProductRepositoryInterface;

    public function delete(Product $product): bool;

    public function findBy(string $key, mixed $values): Collection;
}
