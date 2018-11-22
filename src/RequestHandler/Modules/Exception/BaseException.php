<?php

namespace RequestHandler\Modules\Exception;

/**
 *
 * To use proper error messaging in exceptions extend this class in custom exceptions instead of \Exception
 *
 * @package Core\Exceptions
 */
abstract class BaseException extends \RuntimeException
{

    /** @var array */
    protected $errors;

    /**
     * AFrameworkException constructor.
     * @param int $code Exception Code
     * @param string|null $message Additional message
     * @param \Exception|null $previous
     */
    public function __construct(int $code, string $message = null, ?\Exception $previous = null)
    {

        $exceptionMessage = (isset($this->errors[$code]) ? $this->errors[$code] : 'Unknown error') . ($message ? ": {$message}" : '');

        parent::__construct($exceptionMessage, $code, $previous);
    }
}