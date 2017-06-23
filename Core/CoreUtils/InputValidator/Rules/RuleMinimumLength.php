<?php


namespace Core\CoreUtils\InputValidator\Rules;

/**
 * Description of RuleMinimum
 *
 * @author Aleksandar Zivanovic
 */
class RuleMinimumLength extends InputValidatorRule
{

    private const PARAMETER_MIN = 0;

    /** @var int */
    private $_min = 0;

    /**
     * @param mixed $value
     * @return bool
     */
    public function validate($value): bool
    {

        return null === $value || ($value && strlen($value) >= $this->_min);
    }

    /**
     * @param array $parameters
     * @return InputValidatorRule
     */
    public function setParameters(array $parameters): InputValidatorRule
    {
        if (isset($parameters[self::PARAMETER_MIN])) {

            $this->_min = $parameters[self::PARAMETER_MIN];
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return "Field must contain at least {$this->_min} characters";
    }

}
