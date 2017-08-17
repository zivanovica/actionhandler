<?php

namespace RequestHandler\Modules\Exception;

/**
 *
 * To use proper error messaging in exceptions extend this class in custom exceptions instead of \Exception
 *
 * @package Core\Exceptions
 */
class BaseException extends \RuntimeException
{

    /** @var array */
    protected $_errors;

    /**
     * AFrameworkException constructor.
     * @param int $code Exception Code
     * @param string|null $message Additional message
     * @param \Exception|null $previous
     */
    public function __construct(int $code, string $message = null, ?\Exception $previous = null)
    {

        $exceptionMessage = (isset($this->_errors[$code]) ? $this->_errors[$code] : 'Unknown error') . ($message ? ": {$message}" : '');

        parent::__construct($exceptionMessage, $code, $previous);
    }
}