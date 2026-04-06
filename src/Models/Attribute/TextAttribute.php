<?php

declare(strict_types=1);

namespace App\Models\Attribute;

class TextAttribute extends AbstractAttribute
{
    // type set statically — always 'text'
    public function getType(): string
    {
        return 'text';
    }
    //  Checks if value exists in items list
    public function isValidValue(string $value): bool
    {
        foreach ($this->getItems() as $item) {
            if ($item['value'] === $value) {
                return true;
            }
        }
        return false;
    }

    // Text values displayed as uppercase
    public function formatValue(string $value): string
    {
        return strtoupper($value);
    }
}