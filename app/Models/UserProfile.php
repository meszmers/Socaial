<?php
namespace App\Models;
class UserProfile {
    private $id;
    private $name;
    private $surname;

    public function __construct($id, $name, $surname)
    {

        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
    }


    public function getId()
    {
        return $this->id;
    }


    public function getName()
    {
        return $this->name;
    }

    public function getSurname()
    {
        return $this->surname;
    }
}
