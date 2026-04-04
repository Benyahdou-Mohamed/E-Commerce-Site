<?php

require_once __DIR__ . '/../vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();
use App\Config\Database;

$db = Database::getInstance();
$jsonPath = __DIR__ . '/../scripts/data.json';

if (!file_exists($jsonPath)) {
    die("data.json file not found at $jsonPath");
}

$json = file_get_contents($jsonPath);
$data = json_decode($json, true);

if ($data === null) {
    die("ERROR: Invalid JSON - " . json_last_error_msg() . "\n");
}

echo "Seeding database...\n\n";

// ─── Categories ───────────────────────────────────────────
$categoryIds = [];
$q = $db->prepare("INSERT IGNORE INTO categories (name) VALUES (:name)");

foreach ($data['data']['categories'] as $category) {
    $q->execute([':name' => $category['name']]);
}

$rows = $db->query("SELECT id, name FROM categories")->fetchAll();
foreach ($rows as $row) {
    $categoryIds[$row['name']] = $row['id'];
}

// ─── Attributes ───────────────────────────────────────────
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

        // ✅ Unique attribute id per product
        $uniqueAttrId = $product['id'] . '_' . $attr['id'];

        $attrStmt->execute([
            ':id'   => $uniqueAttrId,
            ':name' => $attr['name'],
            ':type' => $attr['type'],
        ]);

        foreach ($attr['items'] as $item) {

            // ✅ Unique item id per product
            $uniqueItemId = $product['id'] . '_' . $item['id'];

            $attrItemStmt->execute([
                ':id'            => $uniqueItemId,
                ':attribute_id'  => $uniqueAttrId,
                ':display_value' => $item['displayValue'],
                ':value'         => $item['value'],
            ]);
        }
    }
}

// ─── Products ─────────────────────────────────────────────
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

    $categoryName = $product['category'];
    $categoryId   = $categoryIds[$categoryName] ?? null;

    if (!$categoryId) {
        echo "  ⚠️ Category '{$categoryName}' not found, skipping {$product['id']}\n";
        continue;
    }

    // ✅ Type based on attributes not category
    $type = !empty($product['attributes']) ? 'configurable' : 'simple';

    $productStmt->execute([
        ':id'          => $product['id'],
        ':name'        => $product['name'],
        ':in_stock'    => $product['inStock'] ? 1 : 0,
        ':description' => $product['description'],
        ':category_id' => $categoryId,
        ':brand'       => $product['brand'],
        ':type'        => $type,
    ]);

    echo "  ✅ {$product['name']} ({$type})\n";

    // Gallery
    foreach ($product['gallery'] as $imageUrl) {
        $galleryStmt->execute([
            ':product_id' => $product['id'],
            ':image_url'  => $imageUrl,
        ]);
    }

    // Prices
    foreach ($product['prices'] as $price) {
        $priceStmt->execute([
            ':product_id'      => $product['id'],
            ':amount'          => $price['amount'],
            ':currency_label'  => $price['currency']['label'],
            ':currency_symbol' => $price['currency']['symbol'],
        ]);
    }

    // ✅ Link product to UNIQUE attribute id
    foreach ($product['attributes'] as $attr) {
        $uniqueAttrId = $product['id'] . '_' . $attr['id'];

        $productAttrStmt->execute([
            ':product_id'   => $product['id'],
            ':attribute_id' => $uniqueAttrId,
        ]);
    }
}

echo "\n✅ Seeding complete!\n";