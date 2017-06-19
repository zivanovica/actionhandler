<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/19/17
 * Time: 1:09 PM
 */

namespace Api\Middlewares;


use Core\CoreUtils\Singleton;
use Core\Libs\Middleware\IMiddleware;
use Core\Libs\Middleware\Middleware;
use Core\Libs\Request;

class AuthenticateMiddleware implements IMiddleware
{

    use Singleton;

    public function run(Request $request, Middleware $middleware): void
    {

        // before

        $middleware->next();

        // after
    }
}