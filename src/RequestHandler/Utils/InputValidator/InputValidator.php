<?php

namespace RequestHandler\Utils\InputValidator;

use RequestHandler\Modules\Database\IDatabase;
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
    private $_fields = [];

    /**
     * InputValidator constructor.
     * @param array $input
     */
    public function __construct(array $input = [])
    {

        $this->_fields = $input;
    }

    /**
     * @param array $fieldRules
     * @param array $fields
     * @return InputValidator
     */
    public function validate(array $fieldRules, array $fields = []): InputValidator
    {
        $this->_fields = empty($fields) ? $this->_fields : $fields;

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

            /* @var $ruleValidator IInputValidatorRule */
            $ruleValidator = (new \ReflectionClass($ruleClass))->newInstance();

            if (false === $ruleValidator instanceof IInputValidatorRule) {

                throw new RuntimeException("Rule {$ruleClass} must implement InputValidatorRule");
            }

            $ruleValidator->setParameters($parameters);

            $field = false === isset($this->_fields[$fieldName]) ? null : $this->_fields[$fieldName];

            if (false === $ruleValidator->validate($this, $field)) {

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
     * @param array $_fields
     * @return IInputValidator
     */
    public function setFields(array $_fields): IInputValidator
    {

        $this->_fields = $_fields;

        return $this;
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

        return $this->_fields;
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

    public function addRule(IInputValidatorRule $rule): IInputValidator
    {

        $this->_definedRules[$rule->getRuleName()] = get_class($rule);

        unset($ru);

        return $this;
    }

    public function addRules(array $rules): IInputValidator
    {

        /** @var IInputValidatorRule $rule */
        foreach ($rules as $rule) {

            $this->addRule($rule);
        }

        return $this;
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
}
