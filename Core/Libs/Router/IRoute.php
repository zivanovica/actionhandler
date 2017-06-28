<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/20/17
 * Time: 2:59 PM
 */

namespace Core\Libs\Router;


use Core\Libs\Application\IApplicationRequestAfterHandler;
use Core\Libs\Application\IApplicationRequestFilter;
use Core\Libs\Application\IApplicationRequestHandler;
use Core\Libs\Application\IApplicationRequestMiddleware;
use Core\Libs\Application\IApplicationRequestValidator;

interface IRoute
{

    const VALIDATOR_VALID = 0;
    const VALIDATOR_URL = 1;
    const VALIDATOR_METHOD = 2;

    /**
     * @return IApplicationRequestHandler|IApplicationRequestMiddleware|IApplicationRequestAfterHandler|IApplicationRequestValidator|IApplicationRequestFilter
     */
    public function handler(): IApplicationRequestHandler;

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