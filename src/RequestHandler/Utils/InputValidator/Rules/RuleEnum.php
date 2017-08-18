<?php

namespace RequestHandler\Utils\InputValidator\Rules;

class RuleEnum extends InputValidatorRule
{

    /** @var array */
    private $_allowed;

    /**
     *
     * @param mixed $value
     * @return bool
     */
    public function validate($value): bool
    {

        return in_array($value, $this->_allowed);
    }

    /**
     *
     * @param array $parameters
     * @return InputValidatorRule
     */
    public function setParameters(array $parameters): InputValidatorRule
    {

        if (false === empty($parameters)) {

            $this->_allowed = $parameters;
        } else {

            throw new \RuntimeException('Enum rule is missing allowed values');
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return 'Value not allowed. Allowed values are ' . implode(', ', $this->_allowed);
    }
}