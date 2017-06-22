<?php


namespace Core\CoreUtils\InputValidator\Rules;

use Core\Libs\Database;

/**
 * Description of RuleUniqueEntity
 *
 * @author Zvekete
 */
class RuleUniqueEntity extends InputValidatorRule
{

    const PARAMETER_TABLE = 0;
    const PARAMETER_FIELD = 1;

    /** @var string */
    private $table;

    /** @var string */
    private $field;

    /**
     * @param mixed $value
     * @return bool
     */
    public function validate($value): bool
    {

        $results = Database::getSharedInstance()
            ->fetchAll("SELECT `{$this->field}` FROM `{$this->table}` WHERE `{$this->field}` = ?;", [$value]);

        return empty($results);
    }

    /**
     * @param array $parameters
     * @return InputValidatorRule
     */
    public function setParameters(array $parameters): InputValidatorRule
    {
        if (isset($parameters[self::PARAMETER_TABLE])) {

            $this->table = $parameters[self::PARAMETER_TABLE];
        } else {

            throw new \RuntimeException('Rule table must be defined');
        }

        if (isset($parameters[self::PARAMETER_FIELD])) {

            $this->field = $parameters[self::PARAMETER_FIELD];
        } else {

            throw new \RuntimeException('Rule field must be defined');
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return 'Field must be unique';
    }
}
