<?php


namespace Core\CoreUtils\InputValidator\Rules;

/**
 * Description of RuleMaximumLength
 *
 * @author Zvekete
 */
class RuleMaximumLength extends InputValidatorRule
{

    const PARAMETER_MAX = 0;

    /** @var int */
    private $max;

    /**
     * @param mixed $value
     * @return bool
     */
    public function validate($value): bool
    {
        return strlen($value) <= $this->max;
    }

    /**
     * @param array $parameters
     * @return InputValidatorRule
     */
    public function setParameters(array $parameters): InputValidatorRule
    {
        $this->max = PHP_INT_MAX;

        if (isset($parameters[self::PARAMETER_MAX])) {

            $this->max = $parameters[self::PARAMETER_MAX];
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return "Field can not contain more than {$this->max} characters";
    }

}
