<?php

use RequestHandler\Modules\Application\Application;
use RequestHandler\Modules\Database;
use RequestHandler\Modules\Router\Router;

require_once __DIR__ . '/../vendor/autoload.php';

$app = Application::getSharedInstance(__DIR__ . '/config.json');

/** @var \RequestHandler\Modules\Database $db */
$db = Database::getSharedInstance();

$app->run(
    Router::getSharedInstance()
        ->get('/user/:user_id', \Api\Handlers\User\InfoHandler::class)
        ->get('/idea/:id', \Api\Handlers\Idea\GetHandler::class)
        ->patch('/idea/:id', \Api\Handlers\Idea\UpdateHandler::class)
        ->post('/idea/category', \Api\Handlers\IdeaCategory\CreateHandler::class)
        ->post('/idea', \Api\Handlers\Idea\CreateHandler::class)
        ->post('/user/register', \Api\Handlers\User\RegisterHandler::class)
        ->post('/user/login', \Api\Handlers\User\LoginHandler::class)
        ->get('/e/:id', \Api\Handlers\Entity\GetHandler::class)
);
