<?php

namespace Bulletproof\Filters;

use Api\Models\Unique;
use RequestHandler\Utils\DataFilter\Filters\IntFilter;
use RequestHandler\Utils\DataFilter\IDataFilter;
use RequestHandler\Utils\Factory\Factory;

class UniqueEntityFilter implements IDataFilter
{


    /**
     * @param mixed $value Value that will be filtered
     * @return mixed Filtered value
     */
    public function filter($value)
    {

//        return Unique::getByUniqueId(SingletonFactory::getSharedInstance(IntFilter::class)->filter($value));
    }
}