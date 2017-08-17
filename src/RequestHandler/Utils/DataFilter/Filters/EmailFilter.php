<?php

namespace RequestHandler\Utils\DataFilter\Filters;

use RequestHandler\Utils\DataFilter\IDataFilter;
use RequestHandler\Utils\SingletonFactory\SingletonFactory;

/**
 * Filter that is used to convert value to email
 *
 * @package Core\CoreUtils\DataFilter\Filters
 */
class EmailFilter implements IDataFilter
{



    /**
     *
     * Returns email if its valid, FALSE otherwise
     *
     * @param string $value
     * @return false|string
     */
    public function filter($value)
    {

        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}