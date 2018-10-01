<?php

namespace RequestHandler\Exceptions;

use RequestHandler\Modules\Exception\BaseException;

class DecoratorFactoryException extends BaseException
{
    const
        DECORATOR_NOT_FOUND = 70001,
        DECORATOR_NOT_VALID = 70002,
        BAD_OBJECT_TYPE = 70003,
        OBJECT_TYPE_MISMATCH = 70004;


    protected $_errors = [
        DecoratorFactoryException::DECORATOR_NOT_FOUND => 'Decorator class not found. Class',
        DecoratorFactoryException::DECORATOR_NOT_VALID => 'Class does not implement IDecorator interface. Class',
        DecoratorFactoryException::BAD_OBJECT_TYPE => 'Decorator "decorate" parameter is expected to be object',
        DecoratorFactoryException::OBJECT_TYPE_MISMATCH => 'Typed Decorator object mismatch'
    ];


}