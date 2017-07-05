<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/14/17
 * Time: 2:55 PM
 */

require_once __DIR__ . '/../Core/loader.php';

use Core\Libs\Application\Application;
use Core\Libs\Router\Router;

Application::getSharedInstance(__DIR__ . '/config.json')->run(
    Router::getSharedInstance()
        ->get('/user/:id', \Api\Handlers\User\InfoHandler::class)
        ->get('/idea/:id', \Api\Handlers\Idea\GetHandler::class)
        ->patch('/idea/:id', \Api\Handlers\Idea\UpdateHandler::class)
        ->post('/idea/category', \Api\Handlers\IdeaCategory\CreateHandler::class)
        ->post('/idea', \Api\Handlers\Idea\CreateHandler::class)
        ->post('/user/register', \Api\Handlers\User\RegisterHandler::class)
        ->post('/user/login', \Api\Handlers\User\LoginHandler::class)
        ->get('/e/:id', \Api\Handlers\Entity\GetHandler::class)
);

