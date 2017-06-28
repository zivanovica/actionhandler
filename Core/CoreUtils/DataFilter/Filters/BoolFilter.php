<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/16/17
 * Time: 3:07 PM
 */

namespace Core\CoreUtils\DataFilter\Filters;


use Core\CoreUtils\DataFilter\IDataFilter;
use Core\CoreUtils\Singleton;

class BoolFilter implements IDataFilter
{

    use Singleton;

    public function filter($value)
    {

        return filter_var($value, FILTER_VALIDATE_BOOLEAN);
    }
}