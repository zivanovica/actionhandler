<?php

namespace RequestHandler\Utils\InputValidator;

interface IInputValidator
{

    /**
     * @param array $fieldRules
     * @param array $fields
     * @return InputValidator
     */
    public function validate(array $fieldRules, array $fields = []): InputValidator;

    /**
     *
     * @param string $fieldName
     * @param array $rules
     */
    public function validateField($fieldName, array $rules): void;

    /**
     * @return array
     */
    public function getFields(): array;

    /**
     *
     * Set fields that will be validated
     *
     * @param array $fields
     * @return IInputValidator
     */
    public function setFields(array $fields): IInputValidator;

    /**
     *
     * @return array
     */
    public function getErrors(): array;

    /**
     *
     * @return bool
     */
    public function hasErrors(): bool;

    /**
     * @return array
     */
    public function getInput(): array;

    /**
     *
     *
     * @param string $field
     * @param $errorType
     * @return bool
     */
    public function hasError(string $field, ?string $errorType = null): bool;

    /**
     *
     * Add (register) new validator rule
     *
     * @param IInputValidatorRule $rule
     * @return IInputValidator
     */
    public function addRule(IInputValidatorRule $rule): IInputValidator;

    /**
     *
     * Add (register) multiple validator rules
     *
     * @param array $rules
     * @return IInputValidator
     */
    public function addRules(array $rules): IInputValidator;
}