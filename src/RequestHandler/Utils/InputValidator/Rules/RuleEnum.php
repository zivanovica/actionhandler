<?php

namespace RequestHandler\Utils\InputValidator\Rules;

use RequestHandler\Utils\InputValidator\IInputValidator;
use RequestHandler\Utils\InputValidator\IInputValidatorRule;

class RuleEnum implements IInputValidatorRule
{

    /** @var array */
    private $_allowed;

    /**
     *
     * @param IInputValidator $validator
     * @param mixed $value
     * @return bool
     */
    public function validate(IInputValidator $validator, $value): bool
    {

        return in_array($value, $this->_allowed);
    }

    /**
     *
     * @param array $parameters
     * @return IInputValidatorRule
     */
    public function setParameters(array $parameters): IInputValidatorRule
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

    /**
     *
     * Retrieve name of rule
     *
     * @return string
     */
    public function getRuleName(): string
    {

        return 'enum';
    }
}