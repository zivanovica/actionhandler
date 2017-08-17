<?php

namespace RequestHandler\Exceptions;

use RequestHandler\Modules\Exception\BaseException;

class DecoratorFactoryException extends BaseException
{

    const ERROR_DECORATOR_NOT_FOUND = 70001;
    const ERROR_DECORATOR_NOT_VALID = 70002;
    const ERROR_INVALID_OBJECT_TYPE = 70003;
    const ERROR_OBJECT_TYPE_MISMATCH = 70004;


    protected $_errors = [
        DecoratorFactoryException::ERROR_DECORATOR_NOT_FOUND => 'Decorator class not found. Class',
        DecoratorFactoryException::ERROR_DECORATOR_NOT_VALID => 'Class does not implement IDecorator interface. Class',
        DecoratorFactoryException::ERROR_INVALID_OBJECT_TYPE => 'Decorator "decorate" parameter is expected to be object',
        DecoratorFactoryException::ERROR_OBJECT_TYPE_MISMATCH => 'Typed Decorator object mismatch'
    ];


}