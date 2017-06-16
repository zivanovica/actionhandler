<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/16/17
 * Time: 3:07 PM
 */

namespace Core\CoreUtils\DataTransformer\Transformers;


use Core\CoreUtils\DataTransformer\IDataTransformer;
use Core\CoreUtils\Singleton;

class BoolTransformer implements IDataTransformer
{

    use Singleton;

    public function transform($value)
    {
        return (bool) $value;
    }
}