<?php

namespace Core\CoreUtils\DataFilter\Filters;

use Core\CoreUtils\DataFilter\IDataFilter;
use Core\CoreUtils\Singleton;

/**
 * Filter that is used to convert value to integer
 *
 * @package Core\CoreUtils\DataFilter\Filters
 */
class IntFilter implements IDataFilter
{

    use Singleton;

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