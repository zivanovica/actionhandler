<?php

namespace Core\Libs\Application;

/**
 *
 * Implementing this interface into some "IApplicationRequestHandler" classes will tell "Application" to execute "after" method when request
 * finished (validators, middlewares, handler)
 *
 * It does not affect response directly, only if something changes response data inside of "after" method
 *
 * @package Core\Libs\Application
 */
interface IApplicationRequestAfterHandler
{

    /**
     *
     * Executes after "handle" method, doesn't affect on "handle" execution nor request
     *
     */
    public function after(): void;
}