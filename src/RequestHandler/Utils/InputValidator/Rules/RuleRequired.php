<?php

namespace RequestHandler\Utils\InputValidator\Rules;

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
     * @return InputValidatorRule
     */
    public function setParameters(array $parameters): InputValidatorRule
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
}
