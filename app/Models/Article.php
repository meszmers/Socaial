<?php

namespace App\Models;

class Article {
    private string $title;
    private string $description;
    private int $id;
    private string $createdAt;

    public function __construct(int $id, string $title, string $description, string $createdAt)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->createdAt = $createdAt;
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getCreatedAt(): string
    {
        return $this->createdAt;
    }



}
