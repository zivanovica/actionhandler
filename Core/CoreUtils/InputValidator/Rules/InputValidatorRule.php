<?php


namespace Core\CoreUtils\InputValidator\Rules;

use Core\CoreUtils\InputValidator\InputValidator;

/**
 * Description of InputValidatorRule
 *
 * @author Zvekete
 */
abstract class InputValidatorRule
{

    /** @var InputValidator */
    protected $InputValidator;

    /**
     * InputValidatorRule constructor.
     *
     * @param InputValidator $InputValidator
     */
    public function __construct(InputValidator $InputValidator)
    {

        $this->InputValidator = $InputValidator;
    }

    /**
     *
     * @param mixed $value
     * @return bool
     */
    abstract public function validate($value): bool;

    /**
     *
     * @param array $parameters
     * @return InputValidatorRule
     */
    abstract public function setParameters(array $parameters): InputValidatorRule;

    /**
     * @return string
     */
    abstract public function getMessage(): string;
}
