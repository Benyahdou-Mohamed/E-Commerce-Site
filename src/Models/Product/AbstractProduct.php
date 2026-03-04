<?php

declare(strict_types=1);

namespace App\Models\Product;

abstract class AbstractProduct
{
    private string $id;
    private string $name;
    private bool $inStock;
    private int $categoryId;
    private string $brand;
    //private string $type;
    private array $gallery;

    private array $prices;
    private string $description;

    private array $attributes;
    private string $categoryName;

    public function __construct(array $data)
    {
        $this->id = $data["id"];
        $this->name = $data["name"];
        $this->inStock = (bool) ($data['in_stock'] ?? $data['inStock'] ?? false);
        $this->brand = $data["brand"] ?? "";
        $this->gallery = $data["gallery"] ?? [];
        $this->categoryId   = (int) ($data['category_id']   ?? 0);
        $this->prices = $data["prices"] ?? [];
        $this->description = $data["description"] ?? "";
        $this->attributes  = $data['attributes'] ?? [];
        $this->categoryName = $data['category_name'] ?? '';
    }
    abstract public function canAddToCart(array $selectedAttributes): bool;

    public function getId(): string
    {
        return $this->id;
    }
    public function getName(): string
    {
        return $this->name;
    }
    public function inStock(): bool
    {
        return $this->inStock;
    }
    public function getGallery(): array
    {
        return $this->gallery;
    }
    public function getDescription(): string
    {
        return $this->description;
    }
    public function getBrand(): string
    {
        return $this->brand;
    }
    public function getPrices(): array
    {
        return $this->prices;
    }
    public function getAttributes(): array
    {
        return $this->attributes;
    }
    public function getCategoryId(): int
    {
        return $this->categoryId;
    }
    public function getCategoryName(): string
    {
        return $this->categoryName;
    }



    public function toArray(): array
    {
        return [
            "id" => $this->id,
            'name' => $this->name,
            'inStock' => $this->inStock,
            'gallery' => $this->gallery,
            'description' => $this->description,
            'brand' => $this->brand,
            'prices' => $this->prices,
            'attributes' => $this->attributes,
            'categoryId' => $this->categoryId,
            'category' => $this->categoryName,
        ];
    }
}
