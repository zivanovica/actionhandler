<?php

require_once __DIR__ . '/../vendor/autoload.php';

use RequestHandler\Modules\Application\IApplication;
use RequestHandler\Modules\Router\IRouter;
use RequestHandler\Utils\Factory\Factory;

/** @var \RequestHandler\Modules\Application\IApplication $app */
Factory::create(IApplication::class, __DIR__ . '/config.json')->boot(function (IRouter $router) {

    $router->get('/forum/post/:post_id', \Bulletproof\Handlers\Forum\Thread\GetPost::class);
});
