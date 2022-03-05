<?php
namespace App\Models;

class Comment {

    private $id;
    private $userId;
    private $comment;
    private $createdAt;
    private $name;
    private $surname;

    public function __construct($id, $userId, $comment, $createdAt, $name, $surname)
    {
        $this->id = $id;
        $this->userId = $userId;
        $this->comment = $comment;
        $this->createdAt = $createdAt;
        $this->name = $name;
        $this->surname = $surname;
    }


    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    public function getComment()
    {
        return $this->comment;
    }

    public function getUserId()
    {
        return $this->userId;
    }

    public function getSurname()
    {
        return $this->surname;
    }

    public function getName()
    {
        return $this->name;
    }


    public function getId()
    {
        return $this->id;
    }
}