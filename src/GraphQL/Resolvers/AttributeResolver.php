<?php

namespace App\GraphQL\Resolvers;

use App\Config\Database;
use App\Models\Attribute\AttributeFactory;

class AttributeResolver
{
    public static function getByProductId(string $productId): array
    {
        $db = Database::getInstance();

        // Get attribute sets for this product
        $stmt = $db->prepare("
            SELECT a.id, a.name, a.type
            FROM attributes a
            JOIN product_attributes pa ON a.id = pa.attribute_id
            WHERE pa.product_id = :product_id
        ");
        $stmt->execute([':product_id' => $productId]);
        $attrSets = $stmt->fetchAll();

        $result = [];

        foreach ($attrSets as $attrSet) {
            // Get items for each attribute set
            $itemStmt = $db->prepare("
                SELECT id, display_value, value
                FROM attribute_items
                WHERE attribute_id = :attribute_id
            ");
            $itemStmt->execute([':attribute_id' => $attrSet['id']]);
            $items = $itemStmt->fetchAll();

            $attrData = [
                'id'    => $attrSet['id'],
                'name'  => $attrSet['name'],
                'type'  => $attrSet['type'],
                'items' => array_map(fn($item) => [
                    'id'           => $item['id'],
                    'displayValue' => $item['display_value'],
                    'value'        => $item['value'],
                ], $items),
            ];

            
            $result[] = AttributeFactory::create($attrData)->toArray();
        }

        return $result;
    }
}