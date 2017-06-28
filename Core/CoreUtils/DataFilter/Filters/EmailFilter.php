<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/22/17
 * Time: 1:05 PM
 */

namespace Core\CoreUtils\DataFilter\Filters;


use Core\CoreUtils\DataFilter\IDataFilter;
use Core\CoreUtils\Singleton;

class EmailFilter implements IDataFilter
{

    use Singleton;

    public function filter($value)
    {

        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}