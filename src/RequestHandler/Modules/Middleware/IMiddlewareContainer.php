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
     * Add identified data to bag
     *
     * @param string $identifier
     * @param $object
     * @return IMiddlewareContainer
     */
    public function put(string $identifier, $object): IMiddlewareContainer;

    /**
     *
     * Retrieve value of given identifier from bag
     *
     * @param string $identifier
     * @param null $default
     * @return mixed|null
     */
    public function get(string $identifier, $default = null);

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