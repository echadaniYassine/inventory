<?php

require_once __DIR__ . '/../config/bootstrap.php';

use App\Services\ProductService;
use App\Services\CategoryService;
use App\Services\InventoryService;

// Initialize the container and get the ProductService instance
$container = require __DIR__ . '/../config/container.php';

$inventoryService = $container->get(InventoryService::class);
$categoryService = $container->get(CategoryService::class);
$productService = $container->get(ProductService::class);

$inventoryService->addStock(1, 100);
$product = $productService->getProductById(1);
echo $product->getInfo();



// $category = $categoryService->createCategory('Electronics');

// $product = $productService->createProduct(
//     'Mechanical Keyboard',
//     450,
//     $category->id
// );

// $product = $productService->getProductById($product->id);

// echo $product->getInfo();

// $category = $categoryService->createCategory('Electronics');

// echo "Created category: {$category->name}, ID: {$category->id}" . PHP_EOL;

// $categories = $categoryService->getCategories();

// foreach ($categories as $category) {
//     echo "{$category->id} - {$category->name}" . PHP_EOL;
// }

// /* -------------retrieve all products ----------------- */
// $products = $productService->getProducts();

// foreach ($products as $product) {
//     echo $product->getInfo() . PHP_EOL;
// }


/* -------------- create a new product -------------- */
// $product = $productService->createProduct(
//     'Mechanical Keyboard',
//     450,
//     1
// );
// echo $product->getInfo() . PHP_EOL;


/* -------------- retrieve a product by ID -------------- */
// try {
//     $product = $productService->getProductById(999);

//     echo $product->getInfo();
// } catch (RuntimeException $e) {
//     echo $e->getMessage() . PHP_EOL;
// }

// $productById = $productService->getProductById(19);
// echo $productById->getInfo() . PHP_EOL;


/* -------------- update a product -------------- */
// $product = $productService->getProductById(1);
// $product->setName('Ergonomic Mechanical Keyboard');
// $product->setPrice(500);
// $updatedProduct = $productService->updateProduct($product);
// echo $updatedProduct->getInfo() . PHP_EOL;


/* -------------- delete a product -------------- */
// $deleted = $productService->deleteProduct(1);
// echo $deleted ? "Product deleted successfully." . PHP_EOL : "Failed to delete product." . PHP_EOL;
