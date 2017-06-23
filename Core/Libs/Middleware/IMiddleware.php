<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/19/17
 * Time: 12:56 PM
 */

namespace Core\Libs\Middleware;


use Core\Libs\Request;
use Core\Libs\Response\Response;

interface IMiddleware
{

    public function run(Request $request, Response $response, Middleware $middleware): void;
}