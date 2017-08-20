<?php

namespace RequestHandler\Utils\InputValidator\Rules;

use RequestHandler\Utils\InputValidator\IInputValidatorRule;
use RequestHandler\Utils\InputValidator\InputValidatorRule;

/**
 *
 * This rule is used to validate input field as an email
 *
 * @author Aleksandar Zivanovic
 */
class RuleEmail extends InputValidatorRule
{
    public function validate($value): bool
    {
        return false !== filter_var($value, FILTER_VALIDATE_EMAIL);
    }

    public function setParameters(array $parameters): IInputValidatorRule
    {

        return $this;
    }

    public function getMessage(): string
    {
        return 'Email must be valid format';
    }

    /**
     *
     * Retrieve name of rule
     *
     * @return string
     */
    public function getRuleName(): string
    {

        return 'email';
    }
}
