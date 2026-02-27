<?php

namespace App\Models\Product;

class ProductFactory
{
    public static function create(array $data): AbstractProduct
    {
        // If product has attributes it's configurable, otherwise simple
        $hasAttributes = !empty($data['attributes']);

        if ($hasAttributes) {
            return new ConfigurableProduct($data);
        }

        return new SimpleProduct($data);
    }
}