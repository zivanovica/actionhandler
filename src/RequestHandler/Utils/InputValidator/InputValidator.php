<?php

namespace RequestHandler\Utils\InputValidator;

use RequestHandler\Utils\InputValidator\Rules\InputValidatorRule;
use RequestHandler\Utils\InputValidator\Rules\RuleEmail;
use RequestHandler\Utils\InputValidator\Rules\RuleEntityExists;
use RequestHandler\Utils\InputValidator\Rules\RuleEqual;
use RequestHandler\Utils\InputValidator\Rules\RuleFieldSameAsOther;
use RequestHandler\Utils\InputValidator\Rules\RuleMaximumLength;
use RequestHandler\Utils\InputValidator\Rules\RuleMayNotExists;
use RequestHandler\Utils\InputValidator\Rules\RuleMinimumLength;
use RequestHandler\Utils\InputValidator\Rules\RuleRequired;
use RequestHandler\Utils\InputValidator\Rules\RuleUniqueEntity;
use RuntimeException;

/**
 * Its used to register and execute corresponding filters for request field
 *
 * @author Aleksandar Zivanovic
 */
class InputValidator implements IInputValidator
{

    /** @var array */
    private $errors = [];

    /** @var array */
    private $cleanFields = [];

    /** @var array */
    private $_definedRules = [];

    /** @var array */
    private $fields = [];

    /**
     * InputValidator constructor.
     * @param array $input
     */
    public function __construct(array $input = [])
    {
        $this->fields = $input;

        $this->defineRules();
    }

    /**
     * @param array $fieldRules
     * @param array $fields
     * @return InputValidator
     */
    public function validate(array $fieldRules, array $fields = []): InputValidator
    {
        $this->fields = empty($fields) ? $this->fields : $fields;

        foreach ($fieldRules as $fieldName => $rules) {

            $this->validateField($fieldName, explode('|', $rules));
        }

        return $this;
    }

    /**
     *
     * @param string $fieldName
     * @param array $rules
     */
    public function validateField($fieldName, array $rules): void
    {
        foreach ($rules as $rule) {

            list($ruleClass, $parameters) = $this->parseRule($rule);

            /* @var $ruleValidator InputValidatorRule */
            $ruleValidator = (new \ReflectionClass($ruleClass))->newInstance($this);

            if (false === $ruleValidator instanceof InputValidatorRule) {

                throw new RuntimeException("Rule {$ruleClass} must implement InputValidatorRule");
            }

            $ruleValidator->setParameters($parameters);

            $field = false === isset($this->fields[$fieldName]) ? null : $this->fields[$fieldName];

            if (false === $ruleValidator->validate($field)) {

                $this->errors[$fieldName][$ruleClass] = $ruleValidator->getMessage();
            } else if ($field !== null) {

                $this->cleanFields[$fieldName] = $field;
            }
        }
    }

    /**
     * @return array
     */
    public function getFields(): array
    {
        return $this->cleanFields;
    }

    /**
     *
     * @return array
     */
    public function getErrors(): array
    {

        return $this->errors;
    }

    /**
     *
     * @return bool
     */
    public function hasErrors(): bool
    {

        return false === empty($this->errors);
    }

    /**
     * @return array
     */
    public function getInput(): array
    {

        return $this->fields;
    }

    /**
     *
     *
     * @param string $field
     * @param $errorType
     * @return bool
     */
    public function hasError(string $field, ?string $errorType = null): bool
    {
        if (false == $errorType) {

            return false === empty($this->errors[$field]);
        }

        if (class_exists($errorType)) {

            return false === empty($this->errors[$field][$errorType]);
        }

        return false === empty($this->errors[$field][$this->_definedRules[$errorType]]);
    }

    /**
     *
     * @param string $rule
     * @return array
     * @throws RuntimeException
     */
    private function parseRule($rule): array
    {
        $data = explode(':', $rule);

        if (empty($data[0])) {

            throw new RuntimeException('Missing rule');
        } else if (empty($this->_definedRules[$data[0]])) {

            throw new RuntimeException("Rule {$data[0]} is not defined");
        }

        return [$this->_definedRules[$data[0]], empty($data[1]) ? [] : explode(',', $data[1])];
    }

    private function defineRules(): void
    {
        $this->_definedRules = [
            'min' => RuleMinimumLength::class,
            'max' => RuleMaximumLength::class,
            'unique' => RuleUniqueEntity::class,
            'same' => RuleFieldSameAsOther::class,
            'required' => RuleRequired::class,
            'exists' => RuleEntityExists::class,
            'may-not-exists' => RuleMayNotExists::class,
            'email' => RuleEmail::class,
            'equal' => RuleEqual::class,
        ];
    }

}
