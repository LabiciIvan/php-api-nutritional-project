<?php

use App\Classes\Router;
use App\Classes\Request;
use App\Classes\Application;

require __DIR__ . '/vendor/autoload.php';

$request = new Request($_SERVER['REQUEST_METHOD'], $_SERVER['REQUEST_URI']);

$router = new Router();

$router->get('/', function () {
    echo 'This is root route';
});

$app = new Application($request, $router);

$app->run();
