<?php

namespace App\GraphQL\Resolvers;

use App\Config\Database;
use App\Models\Category\CategoryFactory;

class CategoryResolver
{
    public static function getAll(): array
    {
        $db = Database::getInstance();


        $stmt = $db->prepare(
            'SELECT id, name FROM categories'
        );

        $stmt->execute();

        $categories = [];

        foreach ($stmt->fetchAll() as $row) {
            $categories[] = CategoryFactory::create($row)->toArray();
        }

        return $categories;
    }
}
