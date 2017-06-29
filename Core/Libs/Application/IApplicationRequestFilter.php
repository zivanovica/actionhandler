<?php

namespace Core\Libs\Application;

use Core\Libs\Request\IRequestFilter;

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