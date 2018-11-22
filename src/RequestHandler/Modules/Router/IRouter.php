<?php

namespace RequestHandler\Modules\Router;

interface IRouter
{

    /**
     *
     * Register POST route
     *
     * @param string $route
     * @param string $handlerClass
     * @return IRouter
     */
    public function post(string $route, string $handlerClass): IRouter;

    /**
     *
     * Register GET route
     *
     * @param string $route
     * @param string $handlerClass
     * @return IRouter
     */
    public function get(string $route, string $handlerClass): IRouter;

    /**
     *
     * Register PUT route
     *
     * @param string $route
     * @param string $handlerClass
     * @return IRouter
     */
    public function put(string $route, string $handlerClass): IRouter;

    /**
     *
     * Register DELETE route
     *
     * @param string $route
     * @param string $handlerClass
     * @return IRouter
     */
    public function delete(string $route, string $handlerClass): IRouter;

    /**
     *
     * Register PATCH route
     *
     * @param string $route
     * @param string $handlerClass
     * @return IRouter
     */
    public function patch(string $route, string $handlerClass): IRouter;

    /**
     *
     * Register route on any request method
     *
     * @param string $route
     * @param string $handlerClass
     * @return IRouter
     */
    public function any(string $route, string $handlerClass): IRouter;

    /**
     *
     * Register route for all given methods
     *
     * @param string $route
     * @param array $methods
     * @param string $handlerClass
     * @return IRouter
     */
    public function add(string $route, array $methods, string $handlerClass): IRouter;

    /**
     *
     * Decides and retrieves corresponding IRoute for current request
     *
     * @param string $method
     * @param string $route
     * @return IRoute|null
     */
    public function route(string $method, string $route): ?IRoute;

    /**
     *
     * Retrieve current route
     *
     * @return IRoute
     */
    public function currentRoute(): ?IRoute;
}