<?php

namespace Api\Filters;

use Api\Models\Unique;
use RequestHandler\Utils\DataFilter\Filters\IntFilter;
use RequestHandler\Utils\DataFilter\IDataFilter;
use RequestHandler\Utils\Singleton;

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