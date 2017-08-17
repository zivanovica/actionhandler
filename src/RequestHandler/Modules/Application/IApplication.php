<?php

namespace RequestHandler\Modules\Application;

use RequestHandler\Modules\Router\IRouter;
use RequestHandler\Utils\DataFilter\IDataFilter;

interface IApplication
{

    /**
     * Executes handler for requested action
     *
     * @param IRouter $router
     * @throws \Throwable
     */
    public function run(IRouter $router): void;

    /**
     *
     * Retrieve attribute value
     *
     * @param string $name
     * @param mixed $default
     * @param null|IDataFilter $filter
     * @return mixed|null
     */
    public function getAttribute(string $name, $default = null, ?IDataFilter $filter = null);

    /**
     *
     * Sets attribute value
     *
     * @param string $name
     * @param mixed $value
     * @return IApplication
     */
    public function setAttribute(string $name, $value): IApplication;
}