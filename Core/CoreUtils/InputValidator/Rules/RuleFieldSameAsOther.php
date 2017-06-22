<?php


namespace Core\CoreUtils\InputValidator\Rules;

/**
 * Description of RuleFieldSameAsOther
 *
 * @author Zvekete
 */
class RuleFieldSameAsOther extends InputValidatorRule
{

    const PARAMETER_FIELD = 0;

    /** @var string */
    private $field;

    /**
     * @param mixed $value
     * @return bool
     */
    public function validate($value): bool
    {
        if (empty($this->InputValidator->getInput()[$this->field])) {
            return false;
        }

        $fieldValue = $this->InputValidator->getInput()[$this->field];

        return 0 === strcmp($value, $fieldValue);
    }

    /**
     * @param array $parameters
     * @return InputValidatorRule
     */
    public function setParameters(array $parameters): InputValidatorRule
    {
        if (isset($parameters[self::PARAMETER_FIELD])) {

            $this->field = $parameters[self::PARAMETER_FIELD];
        } else {

            throw new \RuntimeException('Missing parameter');
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return "Field must be same as {$this->field}";
    }

}
