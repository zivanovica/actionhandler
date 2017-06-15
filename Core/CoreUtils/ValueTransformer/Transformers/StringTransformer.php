<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/14/17
 * Time: 4:42 PM
 */

namespace Core\CoreUtils\ValueTransformer\Transformers;


use Core\CoreUtils\Singleton;
use Core\CoreUtils\ValueTransformer\IValueTransformer;

class StringTransformer implements IValueTransformer
{

    use Singleton;

    public function transform($value)
    {

        if (is_string($value)) {

            return $value;
        }

        return (string) $value;
    }
}