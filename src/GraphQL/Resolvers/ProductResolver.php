<?php

namespace App\GraphQL\Resolvers;

use App\Config\Database;
use App\Models\Product\ProductFactory;

class ProductResolver
{
    public static function getAll(?string $categoryName = null): array
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

        foreach ($query->fetchAll() as $row) {
            $row['gallery']    = self::getGallery($row['id']);
            $row['prices']     = self::getPrices($row['id']);
            $row['attributes'] = AttributeResolver::getByProductId($row['id']); 

            $products[] = ProductFactory::create($row)->toArray();
        }

        return $products;
    }

    public static function getById(string $id): array
    {
        $db    = Database::getInstance();
        $query = $db->prepare("
            SELECT p.*, c.name AS category_name
            FROM products p
            JOIN categories c ON p.category_id = c.id
            WHERE p.id = :id
        ");
        $query->execute([':id' => $id]);
        $data = $query->fetch();

        if (!$data) {
            return [];
        }

        $data['gallery']    = self::getGallery($data['id']);
        $data['prices']     = self::getPrices($data['id']);
        $data['attributes'] = AttributeResolver::getByProductId($data['id']); // ← delegated

        return ProductFactory::create($data)->toArray();
    }

    private static function getGallery(string $id): array
    {
        $db    = Database::getInstance();
        $query = $db->prepare("
            SELECT image_url
            FROM product_gallery
            WHERE product_id = :id
        ");
        $query->execute([':id' => $id]);
        return array_column($query->fetchAll(), 'image_url');
    }

    private static function getPrices(string $id): array
    {
        $db    = Database::getInstance();
        $query = $db->prepare("
            SELECT amount, currency_label, currency_symbol
            FROM prices
            WHERE product_id = :id
        ");
        $query->execute([':id' => $id]);

        $prices = [];
        foreach ($query->fetchAll() as $row) {
            $prices[] = [
                'amount'   => (float) $row['amount'],
                'currency' => [
                    'label'  => $row['currency_label'],
                    'symbol' => $row['currency_symbol'],
                ],
            ];
        }
        return $prices;
    }


}