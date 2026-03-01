<?php

namespace App\Models\Category;

abstract class AbstractCategory
{
    protected int $id;
    protected string $name;

    public function __construct(array $data)
    {
        $this->id   = (int) $data['id'];
        $this->name = $data['name'];
    }

    // Each subclass must implement this
    abstract public function isAll(): bool;

    public function getId(): int
    {
        return $this->id;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function toArray(): array
    {
        return [
            'id'   => $this->id,
            'name' => $this->name,
        ];
    }
}