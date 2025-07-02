<?php

namespace App\Models;

class Tag
{
    public int $id;
    public string $name;

    private function __construct()
    {
    }

    public static function fromArray(array $data): self
    {
        $tag = new self();
        $tag->id = $data['id'] ?? 0;
        $tag->name = $data['name'] ?? '';
        return $tag;
    }
}
