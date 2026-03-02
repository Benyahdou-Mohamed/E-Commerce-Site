<?php

declare(strict_types=1);

namespace App\Models\Category;

class SpecificCategory extends AbstractCategory
{
    // Regular category — filters products by name
    public function isAll(): bool
    {
        return false;
    }
}
