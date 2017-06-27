<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/19/17
 * Time: 1:02 PM
 */

namespace Core\Libs\Application;


use Core\Libs\Middleware\Middleware;

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