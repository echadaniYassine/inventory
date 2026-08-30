<?php

namespace App\Repositories;

use App\Models\StockMovement;

interface StockMovementRepositoryInterface
{
    public function create(StockMovement $movement): StockMovement;

    public function findByProductId(int $productId): array;
}