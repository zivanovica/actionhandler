<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/19/17
 * Time: 1:11 PM
 */

namespace Api\Middlewares;


use Core\CoreUtils\Singleton;
use Core\Libs\Middleware\IMiddleware;
use Core\Libs\Middleware\Middleware;
use Core\Libs\Request\Request;
use Core\Libs\Response\Response;

class AuthorizeMiddleware implements IMiddleware
{

    use Singleton;

    public function run(Request $request, Response $response, Middleware $middleware): void
    {

        // before

        $middleware->next();

        // after
    }
}