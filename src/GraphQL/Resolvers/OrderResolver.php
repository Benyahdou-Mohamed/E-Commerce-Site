<?php

namespace App\GraphQL\Resolvers;

use App\Config\Database;
use App\Models\Order\Order;
use App\Models\Order\OrderItem;

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

            // Step 2 — insert each order item
            foreach ($items as $item) {
                $orderItem = new OrderItem([
                    'product_id'          => $item['productId'],
                    'quantity'            => $item['quantity'],
                    'selected_attributes' => $item['selectedAttributes'] ?? "{}",
                ]);

                $stmt = $db->prepare("
                    INSERT INTO order_items 
                        (order_id, product_id, quantity, selected_attributes)
                    VALUES 
                        (:order_id, :product_id, :quantity, :selected_attributes)
                ");

                $stmt->execute([
                    ':order_id'            => $orderId,
                    ':product_id'          => $item['productId'],
                    ':quantity'            => $item['quantity'],
                    ':selected_attributes' => $item['selectedAttributes'] ?? '{}',
                ]);
            }

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
