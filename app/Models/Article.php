<?php

namespace App\Models;

class Article {
    private string $title;
    private string $description;
    private int $id;
    private string $createdAt;
    private int $userId;
    private string $surname;

    public function __construct(int $id, string $title, string $description, string $createdAt, int $userId, string $name, string $surname)
    {
        $this->id = $id;
        $this->title = $title;
        $this->description = $description;
        $this->createdAt = $createdAt;
        $this->userId = $userId;
        $this->name = $name;

        $this->surname = $surname;
    }

    public function getid(): int
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

    public function getUserId(): int
    {
        return $this->userId;
    }


    public function getName()
    {
        return $this->name;
    }

    public function getSurname(): string
    {
        return $this->surname;
    }
}
