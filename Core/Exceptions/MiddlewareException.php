<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/19/17
 * Time: 1:00 PM
 */

namespace Core\Exceptions;


class MiddlewareException extends AFrameworkException
{

    const ERROR_INVALID_MIDDLEWARE = 30001;

    protected $_errors = [
        MiddlewareException::ERROR_INVALID_MIDDLEWARE => 'Class must implement IMiddleware interface'
    ];

}