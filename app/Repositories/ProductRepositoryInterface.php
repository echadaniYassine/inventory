<?php

namespace App\Repositories;

use App\Models\Product;

interface ProductRepositoryInterface
{
    public function findAll(): array;
    public function create(Product $product): Product;
    public function findById(int $id): ?Product;
    public function update(Product $product): void;
    public function delete(int $id): void;
    public function updateQuantity(int $id, int $quantity): void;
}
