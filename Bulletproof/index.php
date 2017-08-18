<?php

use RequestHandler\Utils\SingletonFactory\SingletonFactory;

require_once __DIR__ . '/../vendor/autoload.php';

/** @var \RequestHandler\Modules\Application\IApplication $app */
$app = SingletonFactory::getSharedInstance(\RequestHandler\Modules\Application\IApplication::class, __DIR__ . '/config.json');

/** @var \RequestHandler\Modules\Router\IRouter $router */
$router = SingletonFactory::getSharedInstance(\RequestHandler\Modules\Router\IRouter::class);

$app->run(
    $router
        ->post('/forum/post', \Bulletproof\Handlers\Forum\Post\CreatePost::class)// C
        ->get('/forum/post/:post_id', \Bulletproof\Handlers\Forum\Post\GetPost::class)// R
        ->patch('/forum/post/:post_id', \Bulletproof\Handlers\Forum\Post\CreatePost::class)// U
        ->delete('/forum/post/:post_id', \Bulletproof\Handlers\Forum\Post\CreatePost::class)  // D
);
