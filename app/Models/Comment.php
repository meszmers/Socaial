<?php
namespace App\Models;

class Comment {
    private $articleId;
    private $personId;
    private $comment;
    private $name;
    private $surname;
    private $time;

    public function __construct($articleId, $personId, $comment, $name, $surname, $time)
    {
        $this->articleId = $articleId;
        $this->personId = $personId;
        $this->comment = $comment;
        $this->name = $name;
        $this->surname = $surname;
        $this->time = $time;
    }


    public function getArticleId()
    {
        return $this->articleId;
    }

    public function getComment()
    {
        return $this->comment;
    }


    public function getPersonId()
    {
        return $this->personId;
    }

    public function getSurname()
    {
        return $this->surname;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getTime()
    {
        return $this->time;
    }
}