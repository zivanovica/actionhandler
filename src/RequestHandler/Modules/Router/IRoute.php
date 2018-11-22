<?php

namespace RequestHandler\Modules\Router;

use RequestHandler\Modules\Application\ApplicationRequest\IFilter;
use RequestHandler\Modules\Application\ApplicationRequest\IHandle;
use RequestHandler\Modules\Application\ApplicationRequest\IMiddleware;
use RequestHandler\Modules\Application\ApplicationRequest\IValidate;

/**
 * Interface is used to represent correct way of defining one route data
 *
 * Currently is only used as interface of anonymous class in "Router"
 *
 * @package Core\Libs\Router
 */
interface IRoute
{

    const VALIDATOR_VALID = 0;
    const VALIDATOR_URL = 1;
    const VALIDATOR_METHOD = 2;

    /**
     * @return IHandle|IMiddleware|IValidate|IFilter
     */
    public function handler(): IHandle;

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