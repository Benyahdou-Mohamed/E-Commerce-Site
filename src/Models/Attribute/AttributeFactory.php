<?php

namespace App\Models\Attribute;

class AttributeFactory
{
    private static array $typeMap = [
        'text'   => TextAttribute::class,
        'swatch' => SwatchAttribute::class,
    ];

    public static function create(array $data): AbstractAttribute
    {
        $type  = $data['type'] ?? 'text';
        $class = self::$typeMap[$type] ?? TextAttribute::class;
        return new $class($data);
    }
}