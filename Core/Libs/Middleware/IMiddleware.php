<?php

namespace Core\Libs\Middleware;

use Core\Libs\Request\Request;
use Core\Libs\Response\Response;

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