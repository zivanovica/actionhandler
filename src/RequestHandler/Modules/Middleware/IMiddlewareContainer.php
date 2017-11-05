<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 8/17/17
 * Time: 3:05 PM
 */

namespace RequestHandler\Modules\Middleware;


use RequestHandler\Exceptions\MiddlewareException;

interface IMiddlewareContainer
{

    /**
     *
     * Append another middleware in current execution list
     *
     * @param IMiddlewareHandler $middleware
     * @return IMiddlewareContainer
     */
    public function add(IMiddlewareHandler $middleware): IMiddlewareContainer;

    /**
     *
     * Execute next middleware
     *
     * @throws MiddlewareException
     */
    public function next(): void;

    /**
     *
     * Determine did middleware ($this) executed all middlewares
     *
     * @return bool
     */
    public function finished(): bool;
}