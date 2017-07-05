<?php

namespace Api\Filters;

use Api\Models\Unique;
use Core\CoreUtils\DataFilter\Filters\IntFilter;
use Core\CoreUtils\DataFilter\IDataFilter;
use Core\CoreUtils\Singleton;

class UniqueEntityFilter implements IDataFilter
{

    use Singleton;

    /**
     * @param mixed $value Value that will be filtered
     * @return mixed Filtered value
     */
    public function filter($value)
    {

        return Unique::getByUniqueId(IntFilter::getSharedInstance()->filter($value));
    }
}