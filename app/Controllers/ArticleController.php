<?php

namespace App\Controllers;

use App\Database;
use App\Redirect;
use App\View;
use App\Models\Article;

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

        $data = $connection
            ->createQueryBuilder()
            ->select('id', 'title', 'description', 'created_at')
            ->from('articles')
            ->where('id = '.$vars["id"])
            ->executeQuery()
            ->fetchAllAssociative();


        $article = new Article($data[0]["id"], $data[0]["title"], $data[0]["description"], $data[0]["created_at"]);



        return new View("Articles/show.html",["article" => $article]);
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
}