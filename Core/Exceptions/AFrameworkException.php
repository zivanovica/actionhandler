<?php

namespace Core\Exceptions;

abstract class AFrameworkException extends \Exception
{

    /**
     * @var
     */
    protected $_errors;

    /**
     * AFrameworkException constructor.
     * @param int $code Exception Code
     * @param string|null $message Additional message
     * @param \Exception|null $previous
     */
    public function __construct(int $code, string $message = null, \Exception $previous = null)
    {

        $exceptionMessage = (isset($this->_errors[$code]) ? $this->_errors[$code] : 'Unknown error') . ($message ? ": {$message}" : '');

        parent::__construct($exceptionMessage, $code, $previous);
    }

}