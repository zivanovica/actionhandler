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
    const ERROR_MISSING_PRIMARY = 40002;

    protected $_errors = [
        ModelException::ERROR_INVALID_FIELD => 'Field is not valid',
        ModelException::ERROR_MISSING_PRIMARY => 'Primary key is required'
    ];

}