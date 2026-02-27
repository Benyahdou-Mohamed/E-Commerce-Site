<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\GraphQL\Resolvers\ProductResolver;
use App\GraphQL\Resolvers\CategoryResolver;
echo "Testing ProductResolver...\n\n";

// Test 1 — get all products
echo "=== ALL PRODUCTS ===\n";
$products = ProductResolver::getAll();
echo "Found: " . count($products) . " products\n\n";

foreach ($products as $product) {
    echo "- {$product['name']} | category: {$product['category']}\n";
    echo "  gallery images: " . count($product['gallery']) . "\n";
    echo "  prices: " . count($product['prices']) . "\n";
    echo "  attributes: " . count($product['attributes']) . "\n\n";
}

// Test 2 — get products by category
echo "=== CLOTHES ONLY ===\n";
$clothes = ProductResolver::getAll('clothes');
echo "Found: " . count($clothes) . " products\n\n";

// Test 3 — get single product
echo "=== SINGLE PRODUCT ===\n";
$product = ProductResolver::getById('ps-5');
if ($product) {
    echo "Found: {$product['name']}\n";
    echo "Brand: {$product['brand']}\n";
    echo "InStock: " . ($product['inStock'] ? 'yes' : 'no') . "\n";
    echo "Gallery: " . implode(', ', $product['gallery']) . "\n";
    echo "Brand: {$product['prices']}\n";
    print_r($product['attributes']);
} else {
    echo "Product not found!\n";
}

// Test 3 — get categories
echo "=== CATEGORIES ===\n";
$categories = CategoryResolver::getAll();
foreach ($categories as $cat) {
    echo "- {$cat['id']}: {$cat['name']}\n";
}

use App\GraphQL\Resolvers\OrderResolver;

echo "=== CREATE ORDER ===\n";

$result = OrderResolver::createOrder([
    [
        'productId'          => 'ps-5',
        'quantity'           => 2,
        'selectedAttributes' => ['Color' => '#44FF03', 'Capacity' => '512G'],
    ],
    [
        'productId'          => 'apple-airtag',
        'quantity'           => 1,
        'selectedAttributes' => [],
    ],
]);

echo $result ? "Order created!\n" : "Order failed!\n";