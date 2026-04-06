<?php

declare(strict_types=1);

namespace App\GraphQL\Resolvers;

use App\Config\Database;
use App\Models\Order\Order;
use App\Models\Order\OrderItem;
use App\Models\Product\ProductFactory;

class OrderResolver
{
    public static function createOrder(array $items): bool
    {
        $db = Database::getInstance();

        try {
            // Start transaction
            $db->beginTransaction();

            // Step 1 — insert order to database first to get id
            $stmt = $db->prepare("
                INSERT INTO orders (created_at)
                VALUES (NOW())
            ");
            $stmt->execute();

            $orderId = (int) $db->lastInsertId();

            // Create Order model AFTER we have the id
            $order = new Order([
                'id'         => $orderId,
                'created_at' => date('Y-m-d H:i:s'),
                'items'      => $items,
            ]);

            // Step 2 — validate and insert each order item
            foreach ($items as $item) {

                // Fetch raw product data
                $productData = ProductResolver::getById($item['productId']);

                if (empty($productData)) {
                    throw new \RuntimeException(
                        "Product not found: " . $item['productId']
                    );
                }

                // Create product model using factory
                $product = ProductFactory::create($productData);

                // Check inStock using model method
                if (!$product->inStock()) {
                    throw new \RuntimeException(
                        "Product is out of stock: " . $item['productId']
                    );
                }

                // Decode selected attributes
                $selectedAttributes = $item['selectedAttributes'];
                while (is_string($selectedAttributes)) {
                    $selectedAttributes = json_decode($selectedAttributes, true) ?? [];
                }

                // Validate attributes using model method
                if (!$product->canAddToCart($selectedAttributes)) {
                    throw new \RuntimeException(
                        "Cannot add to cart: " . $item['productId'] .
                        ". Please select all required attributes."
                    );
                }

                // Create OrderItem model
                $orderItem = new OrderItem([
                    'productId'          => $item['productId'],
                    'quantity'           => $item['quantity'],
                    'selectedAttributes' => json_encode($selectedAttributes),
                ]);

                // Use model getters for insert
                $stmt = $db->prepare("
                    INSERT INTO order_items
                        (order_id, product_id, quantity, selected_attributes)
                    VALUES
                        (:order_id, :product_id, :quantity, :selected_attributes)
                ");

                $stmt->execute([
                    ':order_id'            => $order->getId(),
                    ':product_id'          => $orderItem->getProductId(),
                    ':quantity'            => $orderItem->getQuantity(),
                    ':selected_attributes' => $orderItem->getSelectedAttributes(),
                ]);
            }

            // All good — commit
            $db->commit();

            return true;

        } catch (\Throwable $e) {
            $db->rollBack();
            return false;
        }
    }
}