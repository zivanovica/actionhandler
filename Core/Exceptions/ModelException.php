<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/21/17
 * Time: 12:45 PM
 */

namespace Core\Exceptions;


class ModelException extends AFrameworkException
{

    const ERROR_INVALID_FIELD = 40001;

    protected $_errors = [
        ModelException::ERROR_INVALID_FIELD => 'Field is not valid',
    ];

}