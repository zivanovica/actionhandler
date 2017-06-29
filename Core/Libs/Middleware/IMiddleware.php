<?php

namespace Core\Libs\Middleware;

use Core\Libs\Request\Request;
use Core\Libs\Response\Response;

/**
 * Implement this interface to make your class an middleware that can be used in any request
 *
 * @package Core\Libs\Middleware
 */
interface IMiddleware
{

    /**
     *
     * Execute method for current middleware
     *
     * @param Request $request
     * @param Response $response
     * @param Middleware $middleware Used to call "next" method
     */
    public function run(Request $request, Response $response, Middleware $middleware): void;
}