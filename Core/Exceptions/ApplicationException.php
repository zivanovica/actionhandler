<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/14/17
 * Time: 9:47 PM
 */

namespace Core\Exceptions;


class ApplicationException extends AFrameworkException
{

    const ERROR_DUPLICATE_ACTION_HANDLER = 20001;
    const ERROR_INVALID_CONFIG = 20002;

    protected $_errors = [
        ApplicationException::ERROR_DUPLICATE_ACTION_HANDLER => 'Duplicate action',
        ApplicationException::ERROR_INVALID_CONFIG => 'Configuration file is not valid'
    ];

}