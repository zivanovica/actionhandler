<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/14/17
 * Time: 4:28 PM
 */

namespace Core\Exceptions;


class ResponseException extends AFrameworkException
{

    const ERROR_INVALID_STATUS_CODE = 10001;
    const ERROR_INVALID_DATA_TYPE   = 10002;
    const ERROR_INVALID_ERRORS_TYPE = 10003;

    protected $_errors = [
        ResponseException::ERROR_INVALID_STATUS_CODE => 'Invalid status code',
        ResponseException::ERROR_INVALID_DATA_TYPE => 'Data must be an array',
        ResponseException::ERROR_INVALID_ERRORS_TYPE => 'Errors must be an array',
    ];

}