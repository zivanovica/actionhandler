<?php

namespace RequestHandler\Modules\Application\ApplicationRequest;

use RequestHandler\Modules\Middleware\IMiddlewareContainer;

/**
 *
 * Implementing this interface into "IApplicationRequestHandler" will tell "Application" that it should execute middlewares added to
 * "Middleware" class before request handler is executed. If there are some unfinished (not executed) middlewares, script will prevent
 * request handler to be executed, and finish request (send response)
 *
 * @package Core\Libs\Application
 */
interface IMiddleware
{

    /**
     *
     * Used to register all middlewares that should be executed before handling acton
     *
     * @param IMiddlewareContainer $middleware
     * @return IMiddlewareContainer
     */
    public function middleware(IMiddlewareContainer $middleware): IMiddlewareContainer;
}