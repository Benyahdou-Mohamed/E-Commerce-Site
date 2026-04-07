<?php

declare(strict_types=1);

namespace App\Models\Product;

class ConfigurableProduct extends AbstractProduct
{
    public function canAddToCart(array $selectedAttributes): bool
    {
       

        foreach ($this->getAttributes() as $attr) {
            if (empty($selectedAttributes[$attr['id']])) {
                return false;
            }
        }
        return true;
    }
}
