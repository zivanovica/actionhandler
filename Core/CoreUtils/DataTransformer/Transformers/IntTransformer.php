<?php

namespace Core\CoreUtils\DataTransformer\Transformers;

use Core\CoreUtils\DataTransformer\IDataTransformer;
use Core\CoreUtils\Singleton;

class IntTransformer implements IDataTransformer
{

    use Singleton;

    public function transform($value)
    {
        if (is_integer($value)) {

            return $value;
        }

        return (int)$value;
    }
}