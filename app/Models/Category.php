<?php

namespace App\Models;

class Category
{
    public int $id;
    public string $name;

    private function __construct()
    {
    }

    public static function fromArray(array $data): self
    {
        $category = new self();
        $category->id = $data['id'] ?? 0;
        $category->name = $data['name'] ?? '';
        return $category;
    }
}
