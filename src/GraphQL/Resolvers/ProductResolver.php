<?php 

namespace App\GraphQL\Resolvers;

use App\Config\Database;
use App\Models\Product\ProductFactory;


class ProductResolver{
    public static function getAll(?string $categoryName=null):array
    {
        $db = Database::getInstance();

        if ($categoryName && $categoryName !== 'all') {
            $query = $db->prepare("
                SELECT p.*, c.name AS category_name
                FROM products p
                JOIN categories c ON p.category_id = c.id
                WHERE c.name = :category
            ");
            $query->execute([':category' => $categoryName]);
        } else {
            $query = $db->prepare("
                SELECT p.*, c.name AS category_name
                FROM products p
                JOIN categories c ON p.category_id = c.id
            ");
            $query->execute();
        }
        $products = [];
        $products_data = $query->fetchAll();

        foreach ( $products_data as $row) {
            $row['gallery']    = self::getGallery($row['id']);
            $row['prices']     = self::getPrices($row['id']);
            $row['attributes'] = self::getAttributes($row['id']);

            $products[] = ProductFactory::create($row)->toArray();
        }

        return $products; 
    }
    public static function getById(string $id): array
    {
        $db = Database::getInstance();
        $query = $db->prepare("SELECT p.*,c.name AS category_name 
        FROM products p JOIN categories c ON p.category_id = c.id 
        WHERE p.id=:id
        ");
        $query->execute(["id"=>$id]);
        $data = $query->fetch();

        if(!$data){
            return [];
        }
        $data['gallery']  = self::getGallery($data["id"]);
        $data['prices'] = self::getPrices($data["id"]);
        $data['attributes'] = self::getAttributes($data["id"]);

        return ProductFactory::create($data)->toArray();
    }
    // Get product images
    public static function getGallery(string $id):array{
        $db = Database::getInstance();
        $query = $db->prepare("
        SELECT image_url FROM product_gallery 
        where product_id = :id");
        $query->execute([":id"=>$id]);
        $data = $query->fetchAll();
        return array_column($data,'image_url');
    }
     // Get product prices
    public static function getPrices(string $id):array{
        $db = Database::getInstance();
        $query = $db->prepare("
        SELECT amount,currency_label,currency_symbol FROM prices where product_id = :id");
        $query->execute([":id"=>$id]);
        $prices=[];
        $data=$query->fetchAll();
        foreach($data as $d){
            $prices[]=[
                "amount" => $d["amount"],
                "currency_label" => $d["currency_label"],
                "currency_symbol" => $d["currency_symbol"],
            ];
        }
        return $prices;

    }

    // Get product attributes
    private static function getAttributes(string $productId): array
    {
        $db   = Database::getInstance();

        $stmt = $db->prepare("
            SELECT a.id, a.name, a.type
            FROM attributes a
            JOIN product_attributes pa ON a.id = pa.attribute_id
            WHERE pa.product_id = :id
        ");
        $stmt->execute([':id' => $productId]);

        $attributes = [];

        foreach ($stmt->fetchAll() as $attr) {
            $itemStmt = $db->prepare("
                SELECT id, display_value, value
                FROM attribute_items
                WHERE attribute_id = :attribute_id
            ");
            $itemStmt->execute([':attribute_id' => $attr['id']]);

            $items = [];

            foreach ($itemStmt->fetchAll() as $item) {
                $items[] = [
                    'id'           => $item['id'],
                    'displayValue' => $item['display_value'],
                    'value'        => $item['value'],
                ];
            }

            $attributes[] = [
                'id'    => $attr['id'],
                'name'  => $attr['name'],
                'type'  => $attr['type'],
                'items' => $items,
            ];
        }

        return $attributes;
    }
}