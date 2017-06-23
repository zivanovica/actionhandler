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
        ->post('/idea', new \Api\Handlers\Idea\CreateHandler())
        ->patch('/idea/:id', new \Api\Handlers\Idea\UpdateHandler())
        ->post('/user/register', new \Api\Handlers\User\RegisterHandler())
        ->post('/user/login', new \Api\Handlers\User\LoginHandler())
);

