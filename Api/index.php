<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/14/17
 * Time: 2:55 PM
 */

require_once __DIR__ . '/../Core/loader.php';

$handlers = [
    'user' => [
        'test' => [
            'login' => \Api\Handlers\User\LoginActionHandler::getSharedInstance()
        ],
        'register' => \Api\Handlers\User\RegisterActionHandler::getSharedInstance()
    ],
    'idea' => [
        'create' => \Api\Handlers\Idea\CreateActionHandler::getSharedInstance(),
        'update' => \Api\Handlers\Idea\UpdateActionHandler::getSharedInstance(),
        'list' => \Api\Handlers\Idea\ListActionHandler::getSharedInstance()
    ]
];

\Core\Libs\Application\Application::getSharedInstance(__DIR__ . '/config.json')
    ->register($handlers)
    ->run()
;