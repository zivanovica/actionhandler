<?php

namespace Core\CoreUtils\DataFilter\Filters;

use Core\CoreUtils\DataFilter\IDataFilter;
use Core\CoreUtils\Singleton;

/**
 * Filter that is used to convert value to boolean
 *
 * @package Core\CoreUtils\DataFilter\Filters
 */
class BoolFilter implements IDataFilter
{

    use Singleton;

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