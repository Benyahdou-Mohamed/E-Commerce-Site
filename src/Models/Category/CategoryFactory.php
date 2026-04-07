<?php

declare(strict_types=1);

namespace App\Models\Category;

class CategoryFactory
{
    private static array $typeMap = [
        'all'      => AllCategory::class,
        'specific' => SpecificCategory::class,
    ];

    public static function create(array $data): AbstractCategory
    {
        $type  = strtolower($data['name'] ?? '') === 'all' ? 'all' : 'specific';
        $class = self::$typeMap[$type] ?? SpecificCategory::class;
        return new $class($data);
    }
}
