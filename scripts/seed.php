<?php

require_once __DIR__ . '/../vendor/autoload.php';

use App\Config\Database;

$db = Database::getInstance(); 
$jsonPath = __DIR__ . '/../scripts/data.json';
if (!file_exists($jsonPath)){
    die("products.json file not found at $jsonPath");
}
$json = file_get_contents($jsonPath);
$data = json_decode($json, JSON_PRETTY_PRINT);
if ($data === null) {
    die("ERROR: Invalid JSON - " . json_last_error_msg() . "\n");
}
//lse{
//    die(json_encode($data, JSON_PRETTY_PRINT));
//}
echo "Seeding database...\n\n";
// Categories 
$categoryIds = [];
$q = $db->prepare("INSERT IGNORE INTO categories (name) VALUES (:name)");

foreach ($data['data']['categories'] as $category) {
    $q->execute([':name' => $category['name']]);
    $categoryIds[$category['name']] = $db->lastInsertId();
}
$rows = $db->query("SELECT id ,name FROM categories")->fetchAll();
foreach($rows as $row){
    $categoryIds[$row['name']] = $row['id'];
}


// Atributes 
$attrStmt = $db->prepare("
    INSERT IGNORE INTO attributes (id, name, type)
    VALUES (:id, :name, :type)
");

$attrItemStmt = $db->prepare("
    INSERT IGNORE INTO attribute_items (id, attribute_id, display_value, value)
    VALUES (:id, :attribute_id, :display_value, :value)
");

foreach ($data['data']['products'] as $product) {
    foreach ($product['attributes'] as $attr) {
        // Insert attribute set
        $attrStmt->execute([
            ':id'   => $attr['id'], ':name' => $attr['name'],':type' => $attr['type'],]);


        // Insert attribute items
        foreach ($attr['items'] as $item) {
            $attrItemStmt->execute([':id' => $item['id'],':attribute_id'  => $attr['id'],':display_value' => $item['displayValue'],':value'=> $item['value'],]);
        }
    }
}


//
//
//
$productStmt = $db->prepare("
    INSERT IGNORE INTO products (id, name, in_stock, description, category_id, brand, type)
    VALUES (:id, :name, :in_stock, :description, :category_id, :brand, :type)
");

$galleryStmt = $db->prepare("
    INSERT INTO product_gallery (product_id, image_url)
    VALUES (:product_id, :image_url)
");

$priceStmt = $db->prepare("
    INSERT INTO prices (product_id, amount, currency_label, currency_symbol)
    VALUES (:product_id, :amount, :currency_label, :currency_symbol)
");

$productAttrStmt = $db->prepare("
    INSERT IGNORE INTO product_attributes (product_id, attribute_id)
    VALUES (:product_id, :attribute_id)
");

foreach ($data['data']['products'] as $product) {
    // Get the category_id from our map
    $categoryName = $product['category'];
    $categoryId   = $categoryIds[$categoryName] ?? null;

    if (!$categoryId) {
        echo "  ⚠️ Category '{$categoryName}' not found, skipping {$product['id']}\n";
        continue;
    }

    // Determine product type
    $type = $categoryName === 'clothes' ? 'configurable' : 'simple';

    // Insert product
    $productStmt->execute([
        ':id'          => $product['id'],
        ':name'        => $product['name'],
        ':in_stock'    => $product['inStock'] ? 1 : 0,
        ':description' => $product['description'],
        ':category_id' => $categoryId,
        ':brand'       => $product['brand'],
        ':type'        => $type,
    ]);
    echo "  - {$product['name']}\n";

    // Insert gallery images
    foreach ($product['gallery'] as $imageUrl) {
        $galleryStmt->execute([
            ':product_id' => $product['id'],
            ':image_url'  => $imageUrl,
        ]);
    }

    // Insert prices
    foreach ($product['prices'] as $price) {
        $priceStmt->execute([
            ':product_id'      => $product['id'],
            ':amount'          => $price['amount'],
            ':currency_label'  => $price['currency']['label'],
            ':currency_symbol' => $price['currency']['symbol'],
        ]);
    }

    // Link product to attributes
    foreach ($product['attributes'] as $attr) {
        $productAttrStmt->execute([
            ':product_id'  => $product['id'],
            ':attribute_id' => $attr['id'],
        ]);
    }
}