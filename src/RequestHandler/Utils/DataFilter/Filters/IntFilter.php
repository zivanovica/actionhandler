<?php

namespace RequestHandler\Utils\DataFilter\Filters;

use RequestHandler\Utils\DataFilter\IDataFilter;
use RequestHandler\Utils\SingletonFactory\SingletonFactory;

/**
 * Filter that is used to convert value to integer
 *
 * @package Core\CoreUtils\DataFilter\Filters
 */
class IntFilter implements IDataFilter
{



    /**
     *
     * Convert value into integer
     *
     * @param mixed $value
     * @return int
     */
    public function filter($value)
    {
        if (is_integer($value)) {

            return $value;
        }

        return (int)$value;
    }
}