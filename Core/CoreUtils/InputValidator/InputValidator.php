<?php

namespace Core\CoreUtils\InputValidator;

use Core\CoreUtils\InputValidator\Rules\InputValidatorRule;
use Core\CoreUtils\InputValidator\Rules\RuleEmail;
use Core\CoreUtils\InputValidator\Rules\RuleEntityExists;
use Core\CoreUtils\InputValidator\Rules\RuleFieldSameAsOther;
use Core\CoreUtils\InputValidator\Rules\RuleMaximumLength;
use Core\CoreUtils\InputValidator\Rules\RuleMayNotExists;
use Core\CoreUtils\InputValidator\Rules\RuleMinimumLength;
use Core\CoreUtils\InputValidator\Rules\RuleRequired;
use Core\CoreUtils\InputValidator\Rules\RuleUniqueEntity;
use Core\CoreUtils\Singleton;
use RuntimeException;

/**
 * Description of InputValidator
 *
 * @author Aleksandar Zivanovic
 */
class InputValidator
{

    use Singleton;

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
     * @return bool
     */
    public function validate(array $fieldRules, array $fields = []): bool
    {
        $this->fields = empty($fields) ? $this->fields : $fields;

        foreach ($fieldRules as $fieldName => $rules) {

            $this->validateField($fieldName, explode('|', $rules));
        }

        return empty($this->errors);
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

            $field = empty($this->fields[$fieldName]) ? null : $this->fields[$fieldName];

            if (false === $ruleValidator->validate($field)) {

                $this->errors[$fieldName][$ruleClass] = $ruleValidator->getMessage();
            } else if ($field !== null) {

                $this->cleanFields[$fieldName] = $field;
            }
        }
    }

    public function getFields(): array
    {
        return $this->cleanFields;
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
            'email' => RuleEmail::class
        ];
    }

}
