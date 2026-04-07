<?php

declare(strict_types=1);

namespace App\Models\Product;

class ProductFactory
{
    private static array $typeMap = [
        'simple' => SimpleProduct::class,
        'configurable' => ConfigurableProduct::class,
    ];

    public static function create(array $data): AbstractProduct
    {
        // Prefer type from database schema, fall back to attributes check
        $type = $data['type'] ?? (!empty($data['attributes']) ? 'configurable' : 'simple');
        $class = self::$typeMap[$type] ?? SimpleProduct::class;
        return new $class($data);
    }
}
