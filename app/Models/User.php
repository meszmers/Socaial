<?php

class User {
    private $id;
    private $name;
    private $surname;
    private $password;
    private $email;

    public function __construct($id, $name, $surname, $password, $email)
    {

        $this->id = $id;
        $this->name = $name;
        $this->surname = $surname;
        $this->password = $password;
        $this->email = $email;
    }


    public function getId()
    {
        return $this->id;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getPassword()
    {
        return $this->password;
    }

    public function getSurname()
    {
        return $this->surname;
    }
}
