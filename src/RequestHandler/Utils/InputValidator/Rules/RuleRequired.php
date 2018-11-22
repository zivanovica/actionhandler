<?php

namespace RequestHandler\Utils\InputValidator\Rules;

use RequestHandler\Utils\InputValidator\IInputValidator;
use RequestHandler\Utils\InputValidator\IInputValidatorRule;

/**
 *
 * Use this rule to ensure existence of input value
 *
 * @author Aleksandar Zivanovic
 */
class RuleRequired implements IInputValidatorRule
{
    /**
     *
     * @param IInputValidator $validator
     * @param mixed $value
     * @return bool
     */
    public function validate(IInputValidator $validator, $value): bool
    {
        return false === empty($value);
    }

    /**
     * @param array $parameters
     * @return IInputValidatorRule
     */
    public function setParameters(array $parameters): IInputValidatorRule
    {

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return 'Field is required';
    }

    /**
     *
     * Retrieve name of rule
     *
     * @return string
     */
    public function getRuleName(): string
    {

        return 'required';
    }
}
