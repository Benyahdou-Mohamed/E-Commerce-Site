<?php

declare(strict_types=1);

namespace App\Models\Product;

class SimpleProduct extends AbstractProduct
{
    public function canAddToCart(array $selectedAttributes): bool
    {
        return true;
    }
}
