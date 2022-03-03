<?php

require_once 'vendor/autoload.php';

use App\Redirect;
use App\View;
use Twig\Environment;
use Twig\Loader\FilesystemLoader;

session_start();

$dispatcher = FastRoute\simpleDispatcher(function(FastRoute\RouteCollector $r) {

    $r->addRoute('GET', '/', [App\Controllers\UsersController::class, "logout"]);

    //Article Routes
    $r->addRoute('GET', '/articles', [App\Controllers\ArticleController::class, "index"]);
    $r->addRoute('GET', '/articles/{id:\d+}', [App\Controllers\ArticleController::class, 'show']);

    $r->addRoute('GET', '/articles/create', [App\Controllers\ArticleController::class, 'create']);
    $r->addRoute('POST', '/articles', [App\Controllers\ArticleController::class, 'store']);
    $r->addRoute('POST', '/articles/{id:\d+}/delete', [App\Controllers\ArticleController::class, 'delete']);
    $r->addRoute('POST', '/articles/{id:\d+}/comment', [App\Controllers\ArticleController::class, 'comment']);

    $r->addRoute('GET', '/articles/{id:\d+}/edit', [App\Controllers\ArticleController::class, 'edit']);
    $r->addRoute('POST', '/articles/{id:\d+}', [App\Controllers\ArticleController::class, 'update']);

    //User Routes
    $r->addRoute('GET', '/register', [App\Controllers\UsersController::class, "register"]);
    $r->addRoute('POST', '/register/true', [App\Controllers\UsersController::class, "addToDataBase"]);
    $r->addRoute('GET', '/login', [App\Controllers\UsersController::class, "login"]);
    $r->addRoute('POST', '/login/true', [App\Controllers\UsersController::class, "loginValidation"]);
    $r->addRoute('GET', '/logout', [App\Controllers\UsersController::class, "logout"]);

    $r->addRoute('GET', '/users', [App\Controllers\UsersController::class, "index"]);
    $r->addRoute('GET', '/users/{userid:\d+}', [App\Controllers\UsersController::class, "show"]);




});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);
switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        break;
    case FastRoute\Dispatcher::FOUND:

        $controller = $routeInfo[1][0];
        $method = $routeInfo[1][1];

        $vars = $routeInfo[2];


        $response = (new $controller)->$method($vars);


        $twig = new Environment(new FilesystemLoader('app/Views'));


        if($response instanceof View) {
            echo $twig->render($response->getPath(), $response->getVars());
        }

        if($response instanceof Redirect) {
            header('Location: ' . $response->getLocation());
        }


        break;
}