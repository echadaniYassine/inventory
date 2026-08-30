<?php

namespace App\Services;

use App\Repositories\CategoryRepositoryInterface;
use App\Models\Category;

class CategoryService
{
    public function __construct(
        private CategoryRepositoryInterface $repository
    ) {}

    public function getCategories(): array
    {
        return $this->repository->findAll();
    }

    public function createCategory(string $name): Category
    {
        if (trim($name) === '') {
            throw new \InvalidArgumentException(
                'Category name cannot be empty.'
            );
        }

        $category = new Category(
            null,
            $name
        );

        return $this->repository->create($category);
    }

    public function getCategoryById(int $id): Category
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException(
                'Category ID must be greater than 0.'
            );
        }

        $category = $this->repository->findById($id);

        if ($category === null) {
            throw new \RuntimeException(
                "Category with ID {$id} not found."
            );
        }

        return $category;
    }

    public function updateCategory(Category $category): Category
    {
        if ($category->id === null) {
            throw new \InvalidArgumentException(
                'Category ID is required for update.'
            );
        }

        if (trim($category->name) === '') {
            throw new \InvalidArgumentException(
                'Category name cannot be empty.'
            );
        }

        $existingCategory = $this->repository->findById(
            $category->id
        );

        if ($existingCategory === null) {
            throw new \RuntimeException(
                "Category with ID {$category->id} not found."
            );
        }

        $this->repository->update($category);

        return $category;
    }

    public function deleteCategory(int $id): void
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException(
                'Category ID must be greater than 0.'
            );
        }

        $category = $this->repository->findById($id);

        if ($category === null) {
            throw new \RuntimeException(
                "Category with ID {$id} not found."
            );
        }

        $this->repository->delete($id);
    }
}