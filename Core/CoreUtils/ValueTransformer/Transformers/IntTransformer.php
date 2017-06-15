<?php

namespace Core\CoreUtils\ValueTransformer\Transformers;

use Core\CoreUtils\Singleton;
use Core\CoreUtils\ValueTransformer\IValueTransformer;

class IntTransformer implements IValueTransformer
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