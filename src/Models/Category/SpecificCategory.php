<?php

declare(strict_types=1);

namespace App\Models\Category;

class SpecificCategory extends AbstractCategory
{
    // Regular category
    public function isAll(): bool
    {
        return false;
    }
}
