<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/14/17
 * Time: 9:44 PM
 */

namespace Core\Libs\Application;

use Core\Libs\Request\Request;
use Core\Libs\Response\Response;

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