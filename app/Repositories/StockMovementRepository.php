<?php

namespace App\Repositories;

use PDO;
use App\Models\StockMovement;

class StockMovementRepository implements StockMovementRepositoryInterface
{
    public function __construct(
        private PDO $pdo
    ) {}

    public function create(StockMovement $movement): StockMovement
    {
        $stmt = $this->pdo->prepare(
            "INSERT INTO stock_movements
                (product_id, type, quantity)
             VALUES
                (:product_id, :type, :quantity)"
        );

        $stmt->execute([
            'product_id' => $movement->productId,
            'type' => $movement->type,
            'quantity' => $movement->quantity
        ]);

        $movement->id = (int) $this->pdo->lastInsertId();

        return $movement;
    }

    public function findByProductId(int $productId): array
    {
        $stmt = $this->pdo->prepare(
            "SELECT *
             FROM stock_movements
             WHERE product_id = :product_id
             ORDER BY created_at DESC"
        );

        $stmt->execute([
            'product_id' => $productId
        ]);

        $data = $stmt->fetchAll(PDO::FETCH_ASSOC);

        $movements = [];

        foreach ($data as $row) {
            $movements[] = new StockMovement(
                (int) $row['id'],
                (int) $row['product_id'],
                $row['type'],
                (int) $row['quantity']
            );
        }

        return $movements;
    }
}