<?php

namespace App\Repositories;

use PDO;
use App\Models\Product;

class ProductRepository implements ProductRepositoryInterface
{
    public function __construct(
        private PDO $pdo
    ) {}

    public function findAll(): array
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products");
        $stmt->execute();
        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
        $products = [];
        foreach ($data as $row) {
            $products[] = new Product(
                (int) $row['id'],
                $row['name'],
                (float) $row['price'],
                (int) $row['quantity'],
                $row['category_id'] !== null
                    ? (int) $row['category_id']
                    : null
            );
        }
        return $products;
    }

    public function create(Product $product): Product
    {
        $stmt = $this->pdo->prepare("INSERT INTO products (name, price, quantity, category_id) VALUES (:name, :price, :quantity, :category_id)");
        $stmt->execute([
            'name' => $product->name,
            'price' => $product->price,
            'quantity' => $product->quantity,
            'category_id' => $product->categoryId
        ]);

        $product->id = (int) $this->pdo->lastInsertId();

        return $product;
    }

    public function findById(int $id): ?Product
    {
        $stmt = $this->pdo->prepare("SELECT * FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        if ($row) {
            return new Product(
                (int) $row['id'],
                $row['name'],
                (float) $row['price'],
                (int) $row['quantity'],
                $row['category_id'] !== null
                    ? (int) $row['category_id']
                    : null
            );
        }
        return null;
    }

    public function update(Product $product): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE products
         SET name = :name, price = :price, category_id = :category_id
         WHERE id = :id"
        );

        $stmt->execute([
            'name' => $product->name,
            'price' => $product->price,
            'category_id' => $product->categoryId,
            'id' => $product->id
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare("DELETE FROM products WHERE id = :id");
        $stmt->execute(['id' => $id]);
    }
    public function updateQuantity(int $id, int $quantity): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE products
         SET quantity = :quantity
         WHERE id = :id"
        );

        $stmt->execute([
            'quantity' => $quantity,
            'id' => $id
        ]);
    }
}
