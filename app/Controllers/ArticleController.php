<?php

namespace App\Controllers;

use App\Database;
use App\Redirect;
use App\View;
use App\Models\Article;
use App\Models\Comment;

class ArticleController {

    public function index(): View {

        $connection = Database::connection();

        $data = $connection
            ->createQueryBuilder()
            ->select('id', 'title', 'description', 'created_at', 'user_id')
            ->from('articles')
            ->orderBy('created_at', "desc")
            ->executeQuery()
            ->fetchAllAssociative();



        $articles = [];
        foreach ($data as $articleInfo) {
            $author = $connection
                ->createQueryBuilder()
                ->select('name', 'surname')
                ->from('user_profile')
                ->where("user_id = ?")
                ->setParameter(0, $articleInfo["user_id"])
                ->executeQuery()
                ->fetchAllAssociative();

            $articles[] = new Article($articleInfo['id'],
                $articleInfo['title'], $articleInfo['description'],
                $articleInfo["created_at"], $articleInfo["user_id"],
                $author[0]["name"], $author[0]["surname"]);
        }

        return new View("Articles/index.html", ['articles' => $articles, "session" => $_SESSION["login"]]);
    }

    public function show(array $vars): View
    {

        $connection = Database::connection();

        $data= $connection
            ->createQueryBuilder()
            ->select('id', 'title', 'description', 'created_at', "user_id")
            ->from('articles')
            ->where('id = '.$vars["id"])
            ->executeQuery()
            ->fetchAllAssociative();

        $user = $connection->executeQuery('SELECT name, surname FROM user_profile WHERE user_id = '. $data[0]["user_id"])->fetchAllAssociative();



        $article = new Article($data[0]["id"], $data[0]["title"], $data[0]["description"], $data[0]["created_at"], $data[0]["user_id"], $user[0]["name"], $user[0]["surname"]);

        $commentData = $connection
            ->createQueryBuilder()
            ->select('user_id', 'comment', "created_at")
            ->from('comments')
            ->where('article_id = '.$vars["id"])
            ->executeQuery()
            ->fetchAllAssociative();

        $comments = [];

        foreach($commentData as $make) {
          $personData = $connection
                ->createQueryBuilder()
                ->select('name', 'surname')
                ->from('user_profile')
                ->where('user_id = '.$make["user_id"])
                ->executeQuery()
                ->fetchAllAssociative();

          $comments[] = new Comment(
              $vars["id"],
              $make["user_id"],
              $make["comment"],
              $make["created_at"],
              $personData[0]["name"],
              $personData[0]["surname"]);

        }

        return new View("Articles/show.html",["article" => $article, "comments"=> $comments, "session" =>$_SESSION["login"]]);
    }

    public function create() : View
    {
        return new View("Articles/create.html");
    }


    public function store() : Redirect
    {
        Database::connection()->insert('articles', ['title' => $_POST["title"], 'description' => $_POST["text"], "user_id" => $_SESSION["login"]["id"]]);

        return new Redirect("/articles");

    }

    public function edit($vars) : View
    {
        $connection = Database::connection();
        $data= $connection
            ->createQueryBuilder()
            ->select('id', 'title', 'description', 'created_at', "user_id")
            ->from('articles')
            ->where('id = '.$vars["id"])
            ->executeQuery()
            ->fetchAllAssociative();

        $user = $connection->executeQuery('SELECT name, surname FROM user_profile WHERE user_id = '. $data[0]["user_id"])->fetchAllAssociative();


        $article = new Article($data[0]["id"], $data[0]["title"], $data[0]["description"], $data[0]["created_at"], $data[0]["user_id"], $user[0]["name"], $user[0]["surname"]);

        return new View("Articles/edit.html", ["articleData" => $article]);
    }

    public function update($vars): Redirect {

       Database::connection()->update('articles', [
           'title' => $_POST["title"],
           'description' => $_POST["text"]],
           ['id' => $vars["id"]]);

        return new Redirect("/articles/".$vars["id"]);
    }

    public function delete($vars): Redirect
    {
        $conn = Database::connection();
       $user = $conn->executeQuery('SELECT user_id FROM articles WHERE id = ?', [$vars['id']])->fetchAllAssociative();


        if($user[0]["user_id"] == $_SESSION["login"]["id"]) {
            $conn->delete('articles', ['id' => $vars['id']]);
        }


        return new Redirect("/articles");

    }

    public function comment($vars) : Redirect {

        Database::connection()->insert('comments', [
            'article_id' => $vars["id"],
            'user_id' => $_SESSION["login"]["id"],
            "comment" => $_POST["comment"]]);

        return new Redirect("/articles/".$vars["id"]);
    }




}