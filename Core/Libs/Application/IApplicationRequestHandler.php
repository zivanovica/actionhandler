<?php

namespace Core\Libs\Application;

use Core\Libs\Request\Request;
use Core\Libs\Response\Response;

/**
 *
 * Implementing this interface to some class will tell "Application" that this class is request handler and it contain "handle" method
 *
 * @package Core\Libs\Application
 */
interface IApplicationRequestHandler
{

    /**
     *
     * Executes when related action is requested
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function handle(Request $request, Response $response): Response;
}