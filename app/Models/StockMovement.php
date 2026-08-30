<?php

namespace App\Models;

class StockMovement
{
    public function __construct(
        public ?int $id,
        public int $productId,
        public string $type,
        public int $quantity,
    ) {}
}