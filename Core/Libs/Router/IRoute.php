<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/20/17
 * Time: 2:59 PM
 */

namespace Core\Libs\Router;


use Core\Libs\Application\IApplicationActionAfterHandler;
use Core\Libs\Application\IApplicationActionHandler;
use Core\Libs\Application\IApplicationActionMiddleware;
use Core\Libs\Application\IApplicationActionValidator;

interface IRoute
{

    /**
     * @return IApplicationActionHandler|IApplicationActionMiddleware|IApplicationActionAfterHandler|IApplicationActionValidator
     */
    public function handler(): IApplicationActionHandler;

    /**
     * @return array Route parameters
     */
    public function parameters(): array;

    /**
     *
     * Validates is current route one client requested
     *
     * @param string $method GET, POST, PUT, DELETE, PATCH
     * @param string $route Route
     * @return bool
     */
    public function valid(string $method, string $route): bool;

}