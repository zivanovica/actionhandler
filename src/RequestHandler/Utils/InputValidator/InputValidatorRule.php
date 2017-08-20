<?php

namespace RequestHandler\Utils\InputValidator;

/**
 * Extending this class will make child class as actual rule that can be registered to "InputValidator"
 *
 * @author Aleksandar Zivanovic
 */
abstract class InputValidatorRule implements IInputValidatorRule
{

    /** @var InputValidator */
    protected $inputValidator;

    /**
     * InputValidatorRule constructor.
     *
     * @param IInputValidator $InputValidator
     */
    public function __construct(IInputValidator $InputValidator)
    {

        $this->inputValidator = $InputValidator;
    }
}
