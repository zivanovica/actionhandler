<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 8/17/17
 * Time: 5:13 AM
 */

namespace RequestHandler\Utils\DataFilter\Filters;


use RequestHandler\Utils\DataFilter\IDataFilter;
use RequestHandler\Utils\SingletonFactory\SingletonFactory;

class UIntFilter implements IDataFilter
{



    /**
     * @param mixed $value Value that will be filtered
     * @return mixed Filtered value
     */
    public function filter($value)
    {

        return abs($value);
    }
}