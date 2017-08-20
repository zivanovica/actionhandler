<?php

namespace RequestHandler\Utils\InputValidator\Rules;

use RequestHandler\Utils\InputValidator\IInputValidatorRule;
use RequestHandler\Utils\InputValidator\InputValidatorRule;

/**
 *
 * This rule is used to ensure maximum length of input value
 *
 * @author Aleksandar Zivanovic
 */
class RuleMaximumLength extends InputValidatorRule
{

    private const PARAMETER_MAX = 0;

    /** @var int */
    private $_max;

    /**
     * @param mixed $value
     * @return bool
     */
    public function validate($value): bool
    {
        return null === $value || ($value && strlen($value) <= $this->_max);
    }

    /**
     * @param array $parameters
     * @return IInputValidatorRule
     */
    public function setParameters(array $parameters): IInputValidatorRule
    {
        $this->_max = PHP_INT_MAX;

        if (isset($parameters[self::PARAMETER_MAX])) {

            $this->_max = $parameters[self::PARAMETER_MAX];
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return "Field can not contain more than {$this->_max} characters";
    }

    /**
     *
     * Retrieve name of rule
     *
     * @return string
     */
    public function getRuleName(): string
    {

        return 'max';
    }
}
