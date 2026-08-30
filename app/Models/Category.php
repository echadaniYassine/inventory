<?php

namespace App\Models;

class Category
{
    public function __construct(
        public ?int $id,
        public string $name
    ) {}

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getName(): string
    {
        return $this->name;
    }
}