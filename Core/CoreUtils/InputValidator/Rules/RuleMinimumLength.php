<?php


namespace Core\CoreUtils\InputValidator\Rules;

/**
 * Description of RuleMinimum
 *
 * @author Zvekete
 */
class RuleMinimumLength extends InputValidatorRule
{

    const PARAMETER_MIN = 0;

    /** @var int */
    private $min = 0;

    /**
     * @param mixed $value
     * @return bool
     */
    public function validate($value): bool
    {
        return strlen($value) >= $this->min;
    }

    /**
     * @param array $parameters
     * @return InputValidatorRule
     */
    public function setParameters(array $parameters): InputValidatorRule
    {
        if (isset($parameters[self::PARAMETER_MIN])) {

            $this->min = $parameters[self::PARAMETER_MIN];
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return "Field must contain at least {$this->min} characters";
    }

}
