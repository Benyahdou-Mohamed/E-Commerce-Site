<?php

declare(strict_types=1);

namespace App\Models\Attribute;

abstract class AbstractAttribute
{
    private string $id;
    private string $name;
    private string $type;
    private array $items;

    public function __construct(array $data)
    {
        $this->id    = $data['id'];
        $this->name  = $data['name'];
        $this->type  = $data['type'];
        $this->items = $data['items'] ?? [];
    }

    public function getId(): string
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getType(): string
    {
        return $this->type;
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
            'type'  => $this->type,
            'items' => $this->items,
        ];
    }
}
