<?php
namespace App\Models\Category;

class CategoryFactory
{
    private static array $typeMap = [
        'all'     => AllCategory::class,
        'Specific' => SpecificCategory::class,
    ];

    public static function create(array $data): AbstractCategory
    {
        $type  = $data['name'] === 'all' ? 'all' : 'Specific';
        $class = self::$typeMap[$type];
        return new $class($data);
    }
}