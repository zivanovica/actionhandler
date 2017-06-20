<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/14/17
 * Time: 2:55 PM
 */

require_once __DIR__ . '/../Core/loader.php';

$router = \Core\Libs\Router\Router::getSharedInstance(\Core\Libs\Request::getSharedInstance());

$router
    ->get('/user/login', new \Api\Handlers\User\LoginActionHandler())
    ->post('/user/register', new \Api\Handlers\User\RegisterActionHandler())
    ->get('/idea/list', new \Api\Handlers\Idea\ListActionHandler())
    ->get('/idea/:id/update/:step', new \Api\Handlers\Idea\UpdateActionHandler())
    ->post('/idea/create', new \Api\Handlers\Idea\CreateActionHandler());

\Core\Libs\Application\Application::getSharedInstance(__DIR__ . '/config.json')->run();

