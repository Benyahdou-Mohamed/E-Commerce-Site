<?php

declare(strict_types=1);

namespace App\GraphQL\Resolvers;

use App\Config\Database;
use App\Models\Order\Order;
use App\Models\Order\OrderItem;
use App\Models\Product\ProductFactory;  // ← add this

class OrderResolver
{
    public static function createOrder(array $items): bool
    {
        error_log("=== CREATE ORDER CALLED ===");
        error_log("Items: " . json_encode($items));

        $db = Database::getInstance();

        try {
            // Start transaction — if anything fails, nothing gets saved
            $db->beginTransaction();

            // Step 1 — create the order
            $stmt = $db->prepare("
                INSERT INTO orders (created_at)
                VALUES (NOW())
            ");
            $stmt->execute();

            // Get the new order id
            $orderId = (int) $db->lastInsertId();

            // Step 2 — validate and insert each order item
            foreach ($items as $item) {

                // fetch product data from database
                $productData = ProductResolver::getById($item['productId']);
                
                if (empty($productData)) {
                    throw new \RuntimeException(
                        "Product not found: " . $item['productId']
                    );
                }

                // create product model using factory
                $product = ProductFactory::create($productData);

                // decode selected attributes from JSON string to array
                $selectedAttributes = $item['selectedAttributes'];
                while (is_string($selectedAttributes)) {
                    $selectedAttributes = json_decode($selectedAttributes, true) ?? [];
                }
                
                if (!$product->canAddToCart($selectedAttributes)) {
                    throw new \RuntimeException(
                        "Cannot add to cart: " . $item['productId'] .
                        ". Please select all required attributes."
                    );
                }
                error_log("selectedAttributes before OrderItem: " . json_encode($selectedAttributes));
                error_log("type: " . gettype($selectedAttributes));
                // create OrderItem model
                $orderItem = new OrderItem([
                    'productId' => $item['productId'],
                    'quantity'  => $item['quantity'],
                    'selectedAttributes' => json_encode($selectedAttributes),
                ]);


                $stmt = $db->prepare("
                    INSERT INTO order_items 
                        (order_id, product_id, quantity, selected_attributes)
                    VALUES 
                        (:order_id, :product_id, :quantity, :selected_attributes)
                ");

                $stmt->execute([
                    ':order_id'            => $orderId,
                    ':product_id'          => $orderItem->getProductId(),
                    ':quantity'            => $orderItem->getQuantity(),
                    ':selected_attributes' => $orderItem->getSelectedAttributes(),
                ]);
            }
            error_log("OrderItem getSelectedAttributes: " . $orderItem->getSelectedAttributes());

            // All good — save everything
            $db->commit();

            return true;

        } catch (\Throwable $e) {
            // Something went wrong — undo everything
            $db->rollBack();
            error_log("Order error: " . $e->getMessage());
            return false;
        }
    }
}