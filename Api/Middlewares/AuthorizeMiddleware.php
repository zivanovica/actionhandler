<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/19/17
 * Time: 1:11 PM
 */

namespace Api\Middlewares;


use RequestHandler\Utils\Singleton;
use RequestHandler\Modules\Middleware\IMiddleware;
use RequestHandler\Modules\Middleware\Middleware;
use RequestHandler\Modules\Request\Request;
use RequestHandler\Modules\Response\Response;

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