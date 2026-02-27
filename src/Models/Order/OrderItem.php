<?php

namespace App\Models\Order;

class OrderItem
{
    private string $productId;
    private int $quantity;
    private array $selectedAttributes;

    public function __construct(array $data)
    {
        $this->productId          = $data['product_id'];
        $this->quantity           = (int) $data['quantity'];
        $this->selectedAttributes = $data['selected_attributes'] ?? [];
    }

    public function getProductId(): string
    {
        return $this->productId;
    }

    public function getQuantity(): int
    {
        return $this->quantity;
    }

    public function getSelectedAttributes(): array
    {
        return $this->selectedAttributes;
    }

    public function toArray(): array
    {
        return [
            'productId'          => $this->productId,
            'quantity'           => $this->quantity,
            'selectedAttributes' => $this->selectedAttributes,
        ];
    }
}