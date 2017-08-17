<?php

require_once __DIR__ . '/../vendor/autoload.php';

use \RequestHandler\Utils\SingletonFactory\SingletonFactory;

SingletonFactory::setMap([
    \RequestHandler\Modules\Application\IApplication::class => \RequestHandler\Modules\Application\Application::class,
    \RequestHandler\Modules\Database\IDatabase::class => \RequestHandler\Modules\Database\Database::class,
    \RequestHandler\Modules\Request\IRequest::class => \RequestHandler\Modules\Request\Request::class,
    \RequestHandler\Modules\Response\IResponse::class => \RequestHandler\Modules\Response\Response::class,
    \RequestHandler\Modules\Router\IRouter::class => \RequestHandler\Modules\Router\Router::class,
    \RequestHandler\Utils\InputValidator\IInputValidator::class => \RequestHandler\Utils\InputValidator\InputValidator::class,
    \RequestHandler\Modules\Middleware\IMiddlewareContainer::class => \RequestHandler\Modules\Middleware\MiddlewareContainer::class,
    'application' => \RequestHandler\Modules\Application\IApplication::class,
    'database' => \RequestHandler\Modules\Database\IDatabase::class,
    'request' => \RequestHandler\Modules\Request\IRequest::class,
    'response' => \RequestHandler\Modules\Response\IResponse::class,
    'router' => \RequestHandler\Modules\Router\IRouter::class,
    'filter.bool' => \RequestHandler\Utils\DataFilter\Filters\BoolFilter::class
]);

$app = SingletonFactory::getSharedInstanceArgs(
    'application', [__DIR__ . '/config.json']
);

$app->run(
    SingletonFactory::getSharedInstance(\RequestHandler\Modules\Router\IRouter::class)
        ->post('/post', \Bulletproof\Handlers\Forum\Post\Create::class)
);
