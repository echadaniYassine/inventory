<?php

namespace App\Services;

use App\Repositories\ProductRepositoryInterface;
use App\Repositories\StockMovementRepositoryInterface;
use App\Models\StockMovement;
use App\Database\TransactionManagerInterface;

class InventoryService
{
    public function __construct(
        private ProductRepositoryInterface $repository,
        private StockMovementRepositoryInterface $movementRepository,
        private TransactionManagerInterface $transaction

    ) {}

    public function addStock(int $productId, int $quantity): void
    {
        if ($productId <= 0) {
            throw new \InvalidArgumentException(
                'Product ID must be greater than 0.'
            );
        }

        $product = $this->repository->findById($productId);

        if ($product === null) {
            throw new \RuntimeException(
                "Product with ID {$productId} not found."
            );
        }

        $this->transaction->run(function () use ($product, $productId, $quantity) {

            $product->addStock($quantity);

            $this->repository->updateQuantity(
                $productId,
                $product->quantity
            );

            $movement = new StockMovement(
                null,
                $productId,
                'IN',
                $quantity
            );

            $this->movementRepository->create($movement);
        });
    }

    public function removeStock(int $productId, int $quantity): void
    {
        if ($productId <= 0) {
            throw new \InvalidArgumentException(
                'Product ID must be greater than 0.'
            );
        }

        $product = $this->repository->findById($productId);

        if ($product === null) {
            throw new \RuntimeException(
                "Product with ID {$productId} not found."
            );
        }

        $this->transaction->run(function () use ($product, $productId, $quantity) {

            $product->removeStock($quantity);

            $this->repository->updateQuantity(
                $productId,
                $product->quantity
            );

            $movement = new StockMovement(
                null,
                $productId,
                'OUT',
                $quantity
            );

            $this->movementRepository->create($movement);
        });
    }
}
