<?php

namespace Core\Libs\Application;

use Core\Libs\Request\IRequestFilter;

/**
 * Implementing this interface to "IApplicationRequestHandler" will tell "Application" to apply request filters with defined values
 * @package Core\Libs\Application
 */
interface IApplicationRequestFilter
{

    /**
     *
     * Request filter used to transform given fields to specified types
     *
     * @param IRequestFilter $filter
     * @return IRequestFilter
     */
    public function filter(IRequestFilter $filter): IRequestFilter;
}