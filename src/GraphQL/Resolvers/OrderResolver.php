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
            // Wrap full order creation in a transaction for atomicity.
            $db->beginTransaction();

            // 1) Create order row first to obtain generated order id.
            $stmt = $db->prepare("
                INSERT INTO orders (created_at)
                VALUES (NOW())
            ");
            $stmt->execute();

            $orderId = (int) $db->lastInsertId();

            // Map persisted data to model for typed access.
            $order = new Order([
                'id'         => $orderId,
                'created_at' => date('Y-m-d H:i:s'),
                'items'      => $items,
            ]);

            // 2) Validate each item and persist corresponding order_item row.
            foreach ($items as $item) {
                // Resolve product data from catalog domain.
                $productData = ProductResolver::getById($item['productId']);

                if (empty($productData)) {
                    throw new \RuntimeException(
                        "Product not found: " . $item['productId']
                    );
                }

                // Convert to product model to apply domain rules.
                $product = ProductFactory::create($productData);

                // Block checkout for out-of-stock products.
                if (!$product->inStock()) {
                    throw new \RuntimeException(
                        "Product is out of stock: " . $item['productId']
                    );
                }

                // Normalize attributes payload from JSON string/object to array.
                $selectedAttributes = $item['selectedAttributes'];
                while (is_string($selectedAttributes)) {
                    $selectedAttributes = json_decode($selectedAttributes, true) ?? [];
                }

                // Ensure configurable products include required selections.
                if (!$product->canAddToCart($selectedAttributes)) {
                    throw new \RuntimeException(
                        "Cannot add to cart: " . $item['productId'] .
                        ". Please select all required attributes."
                    );
                }

                // Persist a canonical JSON value for selected attributes.
                $orderItem = new OrderItem([
                    'productId'          => $item['productId'],
                    'quantity'           => $item['quantity'],
                    'selectedAttributes' => json_encode($selectedAttributes),
                ]);

                // Insert order item using model getters to keep mapping explicit.
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

            // All checks passed; finalize transaction.
            $db->commit();

            return true;

        } catch (\Throwable $e) {
            // Any error rolls back the entire order.
            $db->rollBack();
            return false;
        }
    }
}