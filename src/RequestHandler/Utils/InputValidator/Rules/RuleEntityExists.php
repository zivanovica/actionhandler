<?php

namespace RequestHandler\Utils\InputValidator\Rules;

use RequestHandler\Modules\Database\IDatabase;
use RequestHandler\Utils\InputValidator\IInputValidator;
use RequestHandler\Utils\InputValidator\IInputValidatorRule;
use RequestHandler\Utils\ObjectFactory\ObjectFactory;

/**
 *
 * This rule is used to validate existence of given value with defined table and field (column) in database
 *
 * @author Aleksandar Zivanovic
 */
class RuleEntityExists implements IInputValidatorRule
{

    private const PARAMETER_TABLE = 0;
    private const PARAMETER_FIELD = 1;
    private const PARAMETER_DELIMITER = 2;

    /** @var string */
    private $_table;

    /** @var string */
    private $_field;

    private $_value;

    /** @var string */
    private $_delimiter;

    /**
     * @param mixed $value
     * @return bool
     */
    public function validate(IInputValidator $validator, $value): bool
    {

        if (null === $value) {

            return true;
        }

        if ($this->_delimiter) {

            return $this->_validateMany(explode($this->_delimiter, trim($value, $this->_delimiter)));
        }

        $this->_value = $value;

        $results = ObjectFactory::create(IDatabase::class)
            ->fetchAll("SELECT `{$this->_field}` FROM `{$this->_table}` WHERE `{$this->_field}` = ?;", [$value]);

        return false === empty($results);
    }

    /**
     * @param array $parameters
     *
     * @return IInputValidatorRule
     */
    public function setParameters(array $parameters): IInputValidatorRule
    {
        if (false === empty($parameters[self::PARAMETER_TABLE])) {

            $this->_table = $parameters[self::PARAMETER_TABLE];
        } else {

            throw new \RuntimeException('Exists Rule: Table must be defined');
        }

        if (false === empty($parameters[self::PARAMETER_FIELD])) {

            $this->_field = $parameters[self::PARAMETER_FIELD];
        } else {

            throw new \RuntimeException('Exists Rule: Field must be defined');
        }

        if (false === empty($parameters[self::PARAMETER_DELIMITER])) {

            $this->_delimiter = $parameters[self::PARAMETER_DELIMITER];
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return "{$this->_field} {$this->_value} in {$this->_table} not found";
    }

    /**
     *
     * Validates multiple entries
     *
     * @param array $values
     * @return bool
     */
    private function _validateMany(array $values): bool
    {

        $values = array_values(array_unique($values));

        $placeholders = implode(', ', array_fill(0, count($values), '?'));

        $query = "SELECT `{$this->_field}` FROM `{$this->_table}` WHERE `{$this->_field}` IN ({$placeholders});";

        $results = ObjectFactory::create(IDatabase::class)->fetchAll($query, $values);

        return count($values) === count($results);
    }

    /**
     *
     * Retrieve name of rule
     *
     * @return string
     */
    public function getRuleName(): string
    {

        return 'exists';
    }
}
