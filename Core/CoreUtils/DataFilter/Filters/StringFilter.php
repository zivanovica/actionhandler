<?php

namespace Core\CoreUtils\DataFilter\Filters;

use Core\CoreUtils\DataFilter\IDataFilter;
use Core\CoreUtils\Singleton;

/**
 * Filter that is used to convert value to string
 *
 * @package Core\CoreUtils\DataFilter\Filters
 */
class StringFilter implements IDataFilter
{

    use Singleton;

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