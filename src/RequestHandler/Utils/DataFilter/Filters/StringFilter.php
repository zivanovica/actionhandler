<?php

namespace RequestHandler\Utils\DataFilter\Filters;

use RequestHandler\Utils\DataFilter\IDataFilter;
use RequestHandler\Utils\SingletonFactory\SingletonFactory;

/**
 * Filter that is used to convert value to string
 *
 * @package Core\CoreUtils\DataFilter\Filters
 */
class StringFilter implements IDataFilter
{



    /**
     *
     * Filters input value to string
     *
     * @param mixed $value
     * @return string
     */
    public function filter($value)
    {

        if (is_string($value)) {

            return $value;
        }

        return (string)$value;
    }
}