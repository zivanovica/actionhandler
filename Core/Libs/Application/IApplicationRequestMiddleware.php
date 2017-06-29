<?php

namespace Core\Libs\Application;

use Core\Libs\Middleware\Middleware;

/**
 *
 * Implementing this interface into "IApplicationRequestHandler" will tell "Application" that it should execute middlewares added to
 * "Middleware" class before request handler is executed. If there are some unfinished (not executed) middlewares, script will prevent
 * request handler to be executed, and finish request (send response)
 *
 * @package Core\Libs\Application
 */
interface IApplicationRequestMiddleware
{

    /**
     *
     * Used to register all middlewares that should be executed before handling acton
     *
     * @param Middleware $middleware
     * @return Middleware
     */
    public function middleware(Middleware $middleware): Middleware;
}