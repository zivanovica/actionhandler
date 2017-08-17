<?php

namespace RequestHandler\Utils\InputValidator\Rules;

/**
 *
 * This rule is used to compare two request inputs and validate that they are the same
 *
 * @author Aleksandar Zivanovic
 */
class RuleFieldSameAsOther extends InputValidatorRule
{

    private const PARAMETER_FIELD = 0;

    /** @var string */
    private $_field;

    /**
     * @param mixed $value
     * @return bool
     */
    public function validate($value): bool
    {
        if (empty($this->inputValidator->getInput()[$this->_field])) {
            return false;
        }

        $fieldValue = $this->inputValidator->getInput()[$this->_field];

        return 0 === strcmp($value, $fieldValue);
    }

    /**
     * @param array $parameters
     * @return InputValidatorRule
     */
    public function setParameters(array $parameters): InputValidatorRule
    {
        if (isset($parameters[self::PARAMETER_FIELD])) {

            $this->_field = $parameters[self::PARAMETER_FIELD];
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
        return "Must be same as {$this->_field}";
    }

}
