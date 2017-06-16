<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/16/17
 * Time: 2:24 PM
 */

namespace Core\Libs\Application;


use Core\Libs\Request;
use Core\Libs\Response\Response;

interface IApplicationActionBeforeHandler
{

    /**
     *
     * Executes before "handle" method, if FALSE is returned "handle" won't be called and request will be finished
     *
     * @param Request $request
     * @param Response $response
     * @return bool
     */
    public function before(Request $request, Response $response): bool;
}