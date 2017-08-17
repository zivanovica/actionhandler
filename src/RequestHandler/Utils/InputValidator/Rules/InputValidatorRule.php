<?php

namespace RequestHandler\Utils\InputValidator\Rules;

use RequestHandler\Utils\InputValidator\InputValidator;

/**
 * Extending this class will make child class as actual rule that can be registered to "InputValidator"
 *
 * @author Aleksandar Zivanovic
 */
abstract class InputValidatorRule
{

    /** @var InputValidator */
    protected $inputValidator;

    /**
     * InputValidatorRule constructor.
     *
     * @param InputValidator $InputValidator
     */
    public function __construct(InputValidator $InputValidator)
    {

        $this->inputValidator = $InputValidator;
    }

    /**
     *
     * @param mixed $value
     * @return bool
     */
    abstract public function validate($value): bool;

    /**
     *
     * @param array $parameters
     * @return InputValidatorRule
     */
    abstract public function setParameters(array $parameters): InputValidatorRule;

    /**
     * @return string
     */
    abstract public function getMessage(): string;
}
