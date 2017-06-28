<?php

namespace Core\CoreUtils\DataFilter\Filters;

use Core\CoreUtils\DataFilter\IDataFilter;
use Core\CoreUtils\Singleton;

class IntFilter implements IDataFilter
{

    use Singleton;

    public function filter($value)
    {
        if (is_integer($value)) {

            return $value;
        }

        return (int)$value;
    }
}