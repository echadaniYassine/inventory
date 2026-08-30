<?php

namespace App\Models;

class Product
{
    public function __construct(
        public ?int $id,
        public string $name,
        public float $price,
        public int $quantity,
        public ?int $categoryId,
        public ?Category $category = null
    ) {}

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function setPrice(float $price): void
    {
        $this->price = $price;
    }

    public function addStock(int $quantity): void
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException(
                'Stock quantity must be greater than 0.'
            );
        }

        $this->quantity += $quantity;
    }

    public function removeStock(int $quantity): void
    {
        if ($quantity <= 0) {
            throw new \InvalidArgumentException(
                'Stock quantity must be greater than 0.'
            );
        }

        if ($quantity > $this->quantity) {
            throw new \RuntimeException(
                'Insufficient stock.'
            );
        }

        $this->quantity -= $quantity;
    }

    public function getInfo(): string
    {
        $categoryName = $this->category?->name ?? 'No category';

        return "{$this->name} - {$this->price} MAD - Stock: {$this->quantity} - Category: {$categoryName}" . PHP_EOL;
    }
}
