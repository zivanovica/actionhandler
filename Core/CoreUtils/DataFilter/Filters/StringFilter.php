<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/14/17
 * Time: 4:42 PM
 */

namespace Core\CoreUtils\DataFilter\Filters;


use Core\CoreUtils\DataFilter\IDataFilter;
use Core\CoreUtils\Singleton;

class StringFilter implements IDataFilter
{

    use Singleton;

    public function filter($value)
    {

        if (is_string($value)) {

            return $value;
        }

        return (string)$value;
    }
}