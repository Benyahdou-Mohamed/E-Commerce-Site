<?php

namespace App\Models\Category;

class AllCategory extends AbstractCategory
{
    // "all" category shows every product
    // regardless of category filter
    public function isAll(): bool
    {
        return true;
    }
}