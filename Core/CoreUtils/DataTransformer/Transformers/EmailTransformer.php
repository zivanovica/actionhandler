<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/22/17
 * Time: 1:05 PM
 */

namespace Core\CoreUtils\DataTransformer\Transformers;


use Core\CoreUtils\DataTransformer\IDataTransformer;
use Core\CoreUtils\Singleton;

class EmailTransformer implements IDataTransformer
{

    use Singleton;

    public function transform($value)
    {

        return filter_var($value, FILTER_VALIDATE_EMAIL);
    }
}