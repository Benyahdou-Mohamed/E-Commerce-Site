<?php

declare(strict_types=1);

namespace App\Models\Category;

class AllCategory extends AbstractCategory
{
    // "all" category shows every product
    public function isAll(): bool
    {
        return true;
    }
}
