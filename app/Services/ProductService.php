<?php

namespace App\Services;

use App\Models\Product;
use App\Repositories\ProductRepositoryInterface;
use App\Repositories\CategoryRepositoryInterface;
use App\Database\TransactionManagerInterface;

class ProductService
{
    public function __construct(
        private ProductRepositoryInterface $repository,
        private CategoryRepositoryInterface $categoryRepository,
        private TransactionManagerInterface $transaction
    ) {}

    public function getProducts(): array
    {
        return $this->repository->findAll();
    }

    public function createProduct(string $name, float $price, int $categoryId = null): Product
    {
        if (trim($name) === '') {
            throw new \InvalidArgumentException("Product name cannot be empty");
        }
        if ($price < 0) {
            throw new \InvalidArgumentException("Product price cannot be negative");
        }
        if ($categoryId !== null) {
            if ($categoryId <= 0) {
                throw new \InvalidArgumentException(
                    'Category ID must be greater than 0.'
                );
            }

            $category = $this->categoryRepository->findById($categoryId);

            if ($category === null) {
                throw new \RuntimeException(
                    "Category with ID {$categoryId} not found."
                );
            }
        }

        $product = new Product(null, $name, $price, 0, $categoryId);

        return $this->repository->create($product);
    }

    public function getProductById(int $id): Product
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException(
                'Product ID must be greater than 0.'
            );
        }

        $product = $this->repository->findById($id);

        if ($product === null) {
            throw new \RuntimeException(
                "Product with ID {$id} not found."
            );
        }

        if ($product->categoryId !== null) {
            $product->category = $this->categoryRepository->findById(
                $product->categoryId
            );
        }

        return $product;
    }

    public function updateProduct(Product $product): Product
    {
        if ($product->id === null) {
            throw new \InvalidArgumentException(
                'Product ID is required for update.'
            );
        }

        if (trim($product->name) === '') {
            throw new \InvalidArgumentException(
                'Product name cannot be empty.'
            );
        }

        if ($product->price < 0) {
            throw new \InvalidArgumentException(
                'Product price cannot be negative.'
            );
        }

        if ($product->categoryId !== null) {
            if ($product->categoryId <= 0) {
                throw new \InvalidArgumentException(
                    'Category ID must be greater than 0.'
                );
            }

            $category = $this->categoryRepository->findById(
                $product->categoryId
            );

            if ($category === null) {
                throw new \RuntimeException(
                    "Category with ID {$product->categoryId} not found."
                );
            }
        }

        $existingProduct = $this->repository->findById($product->id);

        if ($existingProduct === null) {
            throw new \RuntimeException(
                "Product with ID {$product->id} not found."
            );
        }

        $this->repository->update($product);

        return $product;
    }

    public function deleteProduct(int $id): void
    {
        if ($id <= 0) {
            throw new \InvalidArgumentException(
                'Product ID must be greater than 0.'
            );
        }

        $product = $this->repository->findById($id);

        if ($product === null) {
            throw new \RuntimeException(
                "Product with ID {$id} not found."
            );
        }

        $this->repository->delete($id);
    }
}
