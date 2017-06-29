<?php

namespace Core\CoreUtils\InputValidator\Rules;

/**
 *
 * This rule is used to ensure that input value IS NOT DEFINED
 *
 * @author Aleksandar Zivanovic
 */
class RuleMayNotExists extends InputValidatorRule
{

    /**
     * @param mixed $value
     * @return bool
     */
    public function validate($value): bool
    {
        return false === isset($value);
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
        return 'field is not supported';
    }
}
