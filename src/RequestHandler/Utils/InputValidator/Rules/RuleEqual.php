<?php

namespace RequestHandler\Utils\InputValidator\Rules;

use RequestHandler\Utils\InputValidator\IInputValidator;
use RequestHandler\Utils\InputValidator\IInputValidatorRule;

/**
 *
 * This rule is used to compare input value to given as rule parameter
 *
 * @package Core\CoreUtils\InputValidator\Rules
 */
class RuleEqual implements IInputValidatorRule
{

    const PARAMETER_VALUE = 0;

    private $_checkValue;

    /**
     *
     * @param IInputValidator $validator
     * @param mixed $value
     * @return bool
     */
    public function validate(IInputValidator $validator, $value): bool
    {

        return 0 === strcmp($value, $this->_checkValue);
    }

    /**
     *
     * @param array $parameters
     * @return IInputValidatorRule
     */
    public function setParameters(array $parameters): IInputValidatorRule
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

    /**
     *
     * Retrieve name of rule
     *
     * @return string
     */
    public function getRuleName(): string
    {

        return 'equal';
    }
}