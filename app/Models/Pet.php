<?php

namespace App\Models;

class Pet
{
    public int $id;
    public ?Category $category;
    public string $name;
    public array $photoUrls;
    public array $tags;
    public string $status;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->category = isset($data['category']) ? new Category($data['category']) : null;
        $this->name = $data['name'] ?? '';
        $this->photoUrls = $data['photoUrls'] ?? [];
        $this->tags = array_map(fn($tag) => new Tag($tag), $data['tags'] ?? []);
        $this->status = $data['status'] ?? '';
    }
}

class Category
{
    public int $id;
    public string $name;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->name = $data['name'] ?? '';
    }
}

class Tag
{
    public int $id;
    public string $name;

    public function __construct(array $data)
    {
        $this->id = $data['id'] ?? 0;
        $this->name = $data['name'] ?? '';
    }
}
