<?php


namespace Core\CoreUtils\InputValidator\Rules;

use Core\Libs\Database;

/**
 * Description of RuleEntityExists
 *
 * @author Aleksandar Zivanovic
 */
class RuleEntityExists extends InputValidatorRule
{

    private const PARAMETER_TABLE = 0;
    private const PARAMETER_FIELD = 1;

    /** @var string */
    private $_table;

    /** @var string */
    private $_field;

    private $_value;

    /**
     * @param mixed $value
     * @return bool
     */
    public function validate($value): bool
    {

        if (null === $value) {

            return true;
        }

        $this->_value = $value;

        $results = Database::getSharedInstance()
            ->fetchAll("SELECT `{$this->_field}` FROM `{$this->_table}` WHERE `{$this->_field}` = ?;", [$value]);

        return false === empty($results);
    }

    /**
     * @param array $parameters
     *
     * @return InputValidatorRule
     */
    public function setParameters(array $parameters): InputValidatorRule
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

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return "{$this->_field} {$this->_value} in {$this->_table} not found";
    }
}
