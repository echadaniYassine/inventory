<?php

namespace App\Repositories;

use App\Models\Category;

interface CategoryRepositoryInterface
{
    public function findAll(): array;

    public function create(Category $category): Category;

    public function findById(int $id): ?Category;

    public function update(Category $category): void;

    public function delete(int $id): void;
}