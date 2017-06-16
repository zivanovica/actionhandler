<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/14/17
 * Time: 4:42 PM
 */

namespace Core\CoreUtils\DataTransformer\Transformers;


use Core\CoreUtils\Singleton;
use Core\CoreUtils\DataTransformer\IDataTransformer;

class StringTransformer implements IDataTransformer
{

    use Singleton;

    public function transform($value)
    {

        if (is_string($value)) {

            return $value;
        }

        return (string)$value;
    }
}