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
            ->select('id', 'title', 'description', 'created_at')
            ->from('articles')
            ->orderBy('created_at', "desc")
            ->executeQuery()
            ->fetchAllAssociative();

        $articles = [];
        foreach ($data as $articleInfo) {
            $articles[] = new Article($articleInfo['id'], $articleInfo['title'], $articleInfo['description'], $articleInfo["created_at"]);
        }

        return new View("Articles/index.html", ['articles' => $articles]);
    }

    public function show(array $vars): View
    {

        $connection = Database::connection();

        $article = $connection
            ->createQueryBuilder()
            ->select('id', 'title', 'description', 'created_at')
            ->from('articles')
            ->where('id = '.$vars["id"])
            ->executeQuery()
            ->fetchAllAssociative();


        $article = new Article($article[0]["id"], $article[0]["title"], $article[0]["description"], $article[0]["created_at"]);

        $commentData = $connection
            ->createQueryBuilder()
            ->select('person_id', 'comment', "time")
            ->from('comments')
            ->where('article_id = '.$vars["id"])
            ->executeQuery()
            ->fetchAllAssociative();

        $comments = [];

        foreach($commentData as$index=>$make) {
          $personData = $connection
                ->createQueryBuilder()
                ->select('name', 'surname')
                ->from('users')
                ->where('id = '.$make["person_id"])
                ->executeQuery()
                ->fetchAllAssociative();

          $comments[] = new Comment(
              $vars["id"],
              $make["person_id"],
              $make["comment"],
              $personData[0]["name"],
              $personData[0]["surname"],
              $make["time"]);


        }


        return new View("Articles/show.html",["article" => $article, "comments"=> $comments]);
    }

    public function create() : View
    {
        return new View("Articles/create.html");
    }


    public function store() : Redirect
    {
        Database::connection()->insert('articles', ['title' => $_POST["title"], 'description' => $_POST["text"]]);

        return new Redirect("/articles");

    }

    public function edit($vars) : View
    {
        $connection = Database::connection()
            ->createQueryBuilder()
            ->select('id', 'title', 'description', 'created_at')
            ->from('articles')
            ->where('id = '.$vars["id"])
            ->executeQuery()
            ->fetchAllAssociative();


        $article = new Article($connection[0]["id"], $connection[0]["title"], $connection[0]["description"], $connection[0]["created_at"]);

        return new View("Articles/edit.html", ["articleData" => $article]);
    }

    public function update($vars): Redirect {

       Database::connection()->update('articles', ['title' => $_POST["title"], 'description' => $_POST["text"]], ['id' => $vars["id"]]);

        return new Redirect("/articles/".$vars["id"]);
    }

    public function delete($vars): Redirect
    {
        Database::connection()
            ->delete('articles', ['id' => $vars['id']]);

        return new Redirect("/articles");

    }

    public function comment($vars) : Redirect {

        Database::connection()->insert('comments', ['article_id' => $vars["id"], 'person_id' => $_SESSION["login"]["id"], "comment" => $_POST["comment"]]);
        return new Redirect("/articles/".$vars["id"]);
    }
}