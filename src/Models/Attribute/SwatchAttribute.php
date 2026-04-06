<?php

declare(strict_types=1);

namespace App\Models\Attribute;

class SwatchAttribute extends AbstractAttribute
{
    // type set statically — always 'text'
    public function getType(): string
    {
        return 'swatch';
    }
    // Validates hex color format like #FF0000
    public function isValidValue(string $value): bool
    {
        return (bool) preg_match('/^#[0-9A-Fa-f]{6}$/', $value);
    }

    // Hex colors displayed as uppercase
    public function formatValue(string $value): string
    {
        return strtoupper($value);
    }
}