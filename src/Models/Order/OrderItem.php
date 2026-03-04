<?php

declare(strict_types=1);

namespace App\Models\Order;

class OrderItem
{
    private string $productId;
    private int $quantity;
    private string $selectedAttributes;

    public function __construct(array $data)
    {
        $this->productId = $data['productId'];
        $this->quantity  = (int) $data['quantity'];
        $this->selectedAttributes = $data['selectedAttributes'] ?? "{}";
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getSelectedAttributes(): string
    {
        return $this->selectedAttributes;
    }

    public function toArray(): array
    {
        return [
            'productId' => $this->productId,
            'quantity' => $this->quantity,
            'selectedAttributes' => $this->selectedAttributes,
        ];
    }
}
