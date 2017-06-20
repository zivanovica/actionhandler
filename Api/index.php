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
        ->get('/user/login', new \Api\Handlers\User\LoginActionHandler())
        ->post('/user/register', new \Api\Handlers\User\RegisterActionHandler())
        ->get('/idea/list', new \Api\Handlers\Idea\ListActionHandler())
        ->get('/idea/:id/update/:step', new \Api\Handlers\Idea\UpdateActionHandler())
        ->post('/idea/create', new \Api\Handlers\Idea\CreateActionHandler())
        ->any('/idea', new class implements \Core\Libs\Application\IApplicationActionHandler {

            /**
             *
             * Executes when related action is requested
             *
             * @param \Core\Libs\Request $request
             * @param \Core\Libs\Response\Response $response
             */
            public function handle(\Core\Libs\Request $request, \Core\Libs\Response\Response $response): void
            {


                $response->data([
                    'REQUEST_METHOD' => $request->method(),
                    'ALLOWED_METHODS' => Router::getSharedInstance()->currentRoute()
                ]);
            }
        })
);

