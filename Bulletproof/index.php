<?php

require_once __DIR__ . '/../vendor/autoload.php';

use RequestHandler\Utils\ObjectFactory\ObjectFactory;
use \RequestHandler\Modules\Application\IApplication;
use RequestHandler\Modules\Router\IRouter;

/** @var \RequestHandler\Modules\Application\IApplication $app */
$app = ObjectFactory::create(
    IApplication::class, __DIR__ . '/config.json'
);

$app->boot(function (IRouter $router) {
    $router
//        ->post('/forum/post', \Bulletproof\Handlers\Forum\Post\CreatePost::class)// C
        ->get('/forum/post/:post_id', \Bulletproof\Handlers\Forum\Thread\GetPost::class);// R
//        ->patch('/forum/post/:post_id', \Bulletproof\Handlers\Forum\Post\CreatePost::class)// U
//        ->delete('/forum/post/:post_id', \Bulletproof\Handlers\Forum\Post\CreatePost::class)  // D
});
