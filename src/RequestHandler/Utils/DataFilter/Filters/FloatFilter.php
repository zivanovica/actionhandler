<?php

namespace RequestHandler\Utils\DataFilter\Filters;

use RequestHandler\Utils\DataFilter\IDataFilter;

class FloatFilter implements IDataFilter
{

    /**
     * @param mixed $value Value that will be filtered
     * @return mixed Filtered value
     */
    public function filter($value)
    {

        if (false === is_numeric($value)) {

            return null;
        }

        return (float)$value;
    }
}