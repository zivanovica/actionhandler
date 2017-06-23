<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/19/17
 * Time: 1:14 PM
 */

namespace Core\Libs\Application;

use Core\Libs\Request;
use Core\Libs\Response\Response;

interface IApplicationActionValidator
{

    /**
     *
     * Validates should current action be handled or not.
     * Status code returned from validate will be used as response status code.
     * If this method does not return status 200 or IResponseStatus::OK script will end response and won't handle rest of request.
     *
     * NOTE: this is executed AFTER middlewares
     *
     * @param Request $request
     * @param Response $response
     * @return bool
     */
    public function validate(Request $request, Response $response): bool ;
}