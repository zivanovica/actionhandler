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

    protected $_errors = [
          ApplicationException::ERROR_DUPLICATE_ACTION_HANDLER => 'Duplicate action'
    ];

}