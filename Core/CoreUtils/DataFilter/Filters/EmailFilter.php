<?php

namespace Core\CoreUtils\DataFilter\Filters;

use Core\CoreUtils\DataFilter\IDataFilter;
use Core\CoreUtils\Singleton;

/**
 * Filter that is used to convert value to email
 *
 * @package Core\CoreUtils\DataFilter\Filters
 */
class EmailFilter implements IDataFilter
{

    use Singleton;

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