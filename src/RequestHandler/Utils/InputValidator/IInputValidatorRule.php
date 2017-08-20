<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 8/18/17
 * Time: 11:00 PM
 */

namespace RequestHandler\Utils\InputValidator;


interface IInputValidatorRule
{

    /**
     *
     * @param mixed $value
     * @return bool
     */
    public function validate($value): bool;

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