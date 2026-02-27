<?php
namespace App\GraphQL\Resolvers;

use App\Models\Category\CategoryFactory;
use App\Config\Database;


class categoryResolver{
    public static function getAll(): array
    {
        $db   = Database::getInstance();
        $stmt = $db->prepare("SELECT id, name FROM categories");
        $stmt->execute();

        $categories = [];

        foreach ($stmt->fetchAll() as $row) {
            $categories[] = CategoryFactory::create($row)->toArray();
        }

        return $categories;
    }
}

