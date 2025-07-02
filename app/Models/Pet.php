<?php

namespace App\Models;

class Pet
{
    public int $id;
    public ?Category $category;
    public string $name;
    public array $photoUrls;
    /** @var Tag[] */
    public array $tags;
    public string $status;

    private function __construct()
    {
    }

    public static function fromArray(array $data): self
    {
        $pet = new self();
        $pet->id = $data['id'] ?? 0;
        $pet->name = $data['name'] ?? '';
        $pet->status = $data['status'] ?? '';
        $pet->photoUrls = $data['photoUrls'] ?? [];
        $pet->category = isset($data['category']) ? Category::fromArray($data['category']) : null;
        $pet->tags = array_map(fn($tag) => Tag::fromArray($tag), $data['tags'] ?? []);

        return $pet;
    }
}
