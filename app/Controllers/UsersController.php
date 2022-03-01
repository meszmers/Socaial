<?php

namespace App\Controllers;

use App\Database;
use App\Redirect;
use App\View;


class UsersController {

    public function register() :View {
        return new View("Users/register.html");
    }
    public function login() :View {
        return new View("Users/login.html");
    }
    public function addToDataBase(){

       if(!empty($_POST["name"]) && !empty($_POST["surname"]) && !empty($_POST["pwd"])  && !empty($_POST["email"]) && $_POST["pwd"] == $_POST["pwdRepeat"]) {
           Database::connection()->insert('users', [
               'name' => $_POST["name"],
               'surname' => $_POST["surname"],
               "password" => password_hash($_POST["pwd"], PASSWORD_DEFAULT),
               "email" => $_POST["email"]]);

           return new Redirect("/login");
       } else {
           return new Redirect("/register");
       }

    }

    public function loginValidation() {
        $dataBase = Database::connection();
          $resultSet = $dataBase->executeQuery('SELECT * FROM users WHERE email = ?', [$_POST["emailLogin"]]);
        $user = $resultSet->fetchAssociative();

        if(password_verify($_POST["pwdLogin"], $user["password"])) {
            return new Redirect("/articles");
        } else return new Redirect("/login");
    }
}