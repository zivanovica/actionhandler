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
}