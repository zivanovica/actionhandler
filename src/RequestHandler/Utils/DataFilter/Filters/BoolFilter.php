<?php

namespace RequestHandler\Utils\DataFilter\Filters;

use RequestHandler\Utils\DataFilter\IDataFilter;
use RequestHandler\Utils\ObjectFactory\ObjectFactory;

/**
 * Filter that is used to convert value to boolean
 *
 * @package Core\CoreUtils\DataFilter\Filters
 */
class BoolFilter implements IDataFilter
{



    /**
     *
     * Convert value to boolean
     *
     * @param mixed $value
     * @return bool
     */
    public function filter($value)
    {

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}