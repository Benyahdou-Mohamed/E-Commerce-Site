<?php

namespace App\Models\Order;

class Order
{
    private int $id;
    private string $createdAt;
    private array $items;

    public function __construct(array $data)
    {
        $this->id        = (int) $data['id'];
        $this->createdAt = $data['created_at'] ?? '';
        $this->items     = $data['items'] ?? [];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }

    public function getItems(): array
    {
        return $this->items;
    }

    public function toArray(): array
    {
        return [
            'id'        => $this->id,
            'createdAt' => $this->createdAt,
            'items'     => $this->items,
        ];
    }
}
