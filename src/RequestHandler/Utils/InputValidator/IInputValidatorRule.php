<?php

namespace RequestHandler\Utils\InputValidator;

interface IInputValidatorRule
{

    /**
     *
     * @param IInputValidator $validator
     * @param mixed $value
     * @return bool
     */
    public function validate(IInputValidator $validator, $value): bool;

    /**
     *
     * @param array $parameters
     * @return IInputValidatorRule
     */
    public function setParameters(array $parameters): IInputValidatorRule;

    /**
     * @return string
     */
    public function getMessage(): string;

    /**
     *
     * Retrieve name of rule
     *
     * @return string
     */
    public function getRuleName(): string;
}