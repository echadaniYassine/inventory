<?php

use App\Container\Container;
use App\Database\Database;
use App\Repositories\ProductRepository;
use App\Repositories\ProductRepositoryInterface;
use App\Repositories\CategoryRepository;
use App\Repositories\CategoryRepositoryInterface;
use App\Repositories\StockMovementRepository;
use App\Repositories\StockMovementRepositoryInterface;
use App\Database\TransactionManagerInterface;
use App\Database\PdoTransactionManager;

$databaseConfig = require __DIR__ . '/../config/database.php';

$container = new Container();

$container->bind(
    PDO::class,
    function () use ($databaseConfig) {
        $database = new Database($databaseConfig);

        return $database->connect();
    }
);

$container->bind(
    ProductRepositoryInterface::class,
    ProductRepository::class
);

$container->bind(
    CategoryRepositoryInterface::class,
    CategoryRepository::class
);

$container->bind(
    StockMovementRepositoryInterface::class,
    StockMovementRepository::class
);

$container->bind(
    TransactionManagerInterface::class,
    PdoTransactionManager::class
);

return $container;
