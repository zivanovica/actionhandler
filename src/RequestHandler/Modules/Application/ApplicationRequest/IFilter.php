<?php

namespace RequestHandler\Modules\Application\ApplicationRequest;

use RequestHandler\Modules\Request\RequestFilter\IRequestFilter;

/**
 * Implementing this interface to "IApplicationRequestHandler" will tell "Application" to apply request filters with defined values
 * @package Core\Libs\Application
 */
interface IFilter
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