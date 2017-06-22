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
        ->post('/idea/create', new \Api\Handlers\Idea\CreateHandler())
        ->post('/user/register', new \Api\Handlers\User\RegisterHandler())
        ->post('/user/login', new \Api\Handlers\User\LoginHandler())
);

