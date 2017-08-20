<?php

namespace RequestHandler\Utils\InputValidator\Rules;

use RequestHandler\Utils\InputValidator\IInputValidatorRule;
use RequestHandler\Utils\InputValidator\InputValidatorRule;

/**
 *
 * Use this rule to ensure existence of input value
 *
 * @author Aleksandar Zivanovic
 */
class RuleRequired extends InputValidatorRule
{
    /**
     * @param mixed $value
     * @return bool
     */
    public function validate($value): bool
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
