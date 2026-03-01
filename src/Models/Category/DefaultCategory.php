<?php

namespace App\Models\Category;

class DefaultCategory extends AbstractCategory
{
    // Regular category — filters products by name
    public function isAll(): bool
    {
        return false;
    }
}