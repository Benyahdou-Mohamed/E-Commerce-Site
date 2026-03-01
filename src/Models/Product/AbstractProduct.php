<?php

namespace App\Models\Product;

abstract class AbstractProduct
{
    protected string $id;
    protected string $name;
    protected bool $inStock;
    protected int $categoryId;
    protected string $brand;
    //protected string $type;
    protected array $gallery;

    protected array $prices;
    protected string $description;

    protected array $attributes;
    protected string $categoryName;

    public function __construct(array $data)
    {
        $this->id = $data["id"];
        $this->name = $data["name"];
        $this->inStock = (bool) $data["in_stock"];
        $this->brand = $data["brand"] ?? "";
        $this->gallery = $data["gallery"] ?? [];
        $this->categoryId = (int)$data["category_id"];
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
