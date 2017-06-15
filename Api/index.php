<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/14/17
 * Time: 2:55 PM
 */

spl_autoload_register(function ($class) {

    $classFilePath = __DIR__ . '/../' . str_replace('\\', '/', $class) . '.php';

    require_once $classFilePath;
});

$handlers = [
    'user' => [
        'test' => [
            'login' => \Api\Handlers\User\LoginHandler::getSharedInstance()
        ],
        'register' => \Api\Handlers\User\RegisterHandler::getSharedInstance()
    ],
    'idea' => [
        'create' => \Api\Handlers\Idea\CreateHandler::getSharedInstance(),
        'update' => \Api\Handlers\Idea\UpdateHandler::getSharedInstance(),
        'list' => \Api\Handlers\Idea\ListHandler::getSharedInstance()
    ]
];

$application = \Core\Libs\Application\Application::getSharedInstance();

$application->register($handlers)->run();