<?php

declare(strict_types=1);

namespace App\Models\Attribute;

abstract class AbstractAttribute
{
    private string $id;
    private string $name;
    private string $type;
    private array  $items;

    public function __construct(array $data)
    {
        $this->id    = $data['id'];
        $this->name  = $data['name'];
        $this->type  = $data['type'];
        $this->items = $data['items'] ?? [];
    }

    //  Force subclasses to implement 
    abstract public function getType(): string;
    abstract public function isValidValue(string $value): bool;
    abstract public function formatValue(string $value): string;

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

  

    public function getItems(): array
    {
        return $this->items;
    }

    public function toArray(): array
    {
        return [
            'id'    => $this->id,
            'name'  => $this->name,
            'type'  => $this->getType(),
            'items' => $this->items,
        ];
    }
}