<?php

namespace App\Controllers;

use App\Database;
use App\Models\User;
use App\Models\UserProfile;
use App\Redirect;
use App\View;


class UsersController {

    public function register() :View {
        return new View("Users/register.html");
    }
    public function login() :View {

        if(!empty($_SESSION["login"])) {
            return new View("Articles/index.html");
        } else return new View("Users/login.html");
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
            $_SESSION["login"] = ["id" => $user["id"], "name"=>$user["name"], "surname"=>$user["surname"]];
            return new Redirect("/articles");
        } else return new Redirect("/login");
    }

    public function logout(){

        session_destroy();

        return new Redirect("/login");
    }
   public function index() : View{
       $connection = Database::connection()
           ->createQueryBuilder()
           ->select('id', 'name', 'surname')
           ->from('users')
           ->executeQuery()
           ->fetchAllAssociative();
       $users = [];
       foreach ($connection as $model) {
           if($model["id"] == $_SESSION["login"]["id"]) {
               continue;
           }
           $users[] = new UserProfile($model["id"], $model["name"], $model["surname"]);
       }


        return new View("Users/index.html",["users" => $users]);
   }

   public function show($vars) : View {
       $connection = Database::connection()
           ->createQueryBuilder()
           ->select('id', 'name', 'surname')
           ->from('users')
           ->where("id = ". $vars["userid"])
           ->executeQuery()
           ->fetchAllAssociative();

        $user = new UserProfile($connection[0]["id"], $connection[0]["name"], $connection[0]["surname"]);

        return new View("Users/show.html", ["user" => $user]);
   }
}