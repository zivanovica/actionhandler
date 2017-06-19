<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/14/17
 * Time: 2:55 PM
 */

require_once __DIR__ . '/../Core/loader.php';

$handlers = [
    'user' => [ // Group "user"
        'test' => [ // Sub-Grup "test"
            'login' => \Api\Handlers\User\LoginActionHandler::getSharedInstance() // Action "user.test.login"
        ],
        'register' => \Api\Handlers\User\RegisterActionHandler::getSharedInstance() // Action "user.register"
    ],
    'idea' => [ // Group "idea"
        'create' => \Api\Handlers\Idea\CreateActionHandler::getSharedInstance(), // Action "idea.create"
        'update' => \Api\Handlers\Idea\UpdateActionHandler::getSharedInstance(), // Action "idea.update"
        'list' => \Api\Handlers\Idea\ListActionHandler::getSharedInstance() // Action "idea.list"
    ]
];

\Core\Libs\Application\Application::getSharedInstance(__DIR__ . '/config.json')
    ->register($handlers)
    ->run();