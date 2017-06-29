<?php

namespace Core\CoreUtils\InputValidator\Rules;

/**
 *
 * This rule is used to compare input value to given as rule parameter
 *
 * @package Core\CoreUtils\InputValidator\Rules
 */
class RuleEqual extends InputValidatorRule
{

    const PARAMETER_VALUE = 0;

    private $_checkValue;

    /**
     *
     * @param mixed $value
     * @return bool
     */
    public function validate($value): bool
    {

        return 0 === strcmp($value, $this->_checkValue);
    }

    /**
     *
     * @param array $parameters
     * @return InputValidatorRule
     */
    public function setParameters(array $parameters): InputValidatorRule
    {

        if (isset($parameters[RuleEqual::PARAMETER_VALUE])) {

            $this->_checkValue = $parameters[RuleEqual::PARAMETER_VALUE];
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {

        return "Must be equal to {$this->_checkValue}";
    }
}