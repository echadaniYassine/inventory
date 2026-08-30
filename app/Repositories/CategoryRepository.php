<?php

namespace App\Repositories;

use PDO;
use App\Models\Category;

class CategoryRepository implements CategoryRepositoryInterface
{
    public function __construct(
        private PDO $pdo
    ) {}

    public function findAll(): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM categories"
        );

        $stmt->execute();

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $categories = [];

        foreach ($data as $row) {
            $categories[] = new Category(
                (int) $row['id'],
                $row['name']
            );
        }

        return $categories;
    }

    public function create(Category $category): Category
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO categories (name)
             VALUES (:name)"
        );

        $stmt->execute([
            'name' => $category->name
        ]);

        $category->id = (int) $this->pdo->lastInsertId();

        return $category;
    }

    public function findById(int $id): ?Category
    {
        $stmt = $this->pdo->prepare(
            "SELECT * FROM categories WHERE id = :id"
        );

        $stmt->execute([
            'id' => $id
        ]);

        $row = $stmt->fetch(PDO::FETCH_ASSOC);

        if (!$row) {
            return null;
        }

        return new Category(
            (int) $row['id'],
            $row['name']
        );
    }

    public function update(Category $category): void
    {
        $stmt = $this->pdo->prepare(
            "UPDATE categories
             SET name = :name
             WHERE id = :id"
        );

        $stmt->execute([
            'name' => $category->name,
            'id' => $category->id
        ]);
    }

    public function delete(int $id): void
    {
        $stmt = $this->pdo->prepare(
            "DELETE FROM categories
             WHERE id = :id"
        );

        $stmt->execute([
            'id' => $id
        ]);
    }
}