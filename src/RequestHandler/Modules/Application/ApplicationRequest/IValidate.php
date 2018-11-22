<?php

namespace RequestHandler\Modules\Application\ApplicationRequest;

use RequestHandler\Utils\InputValidator\IInputValidator;

/**
 * Implementing this interface into "IApplicationRequestHandler" will tell "Application" to execute "validate" method,
 * before any middlewares and handlers. If validation fails request is finished with status 400 and errors
 *
 * @package Core\Libs\Application
 */
interface IValidate
{

    /**
     *
     * Validator is used to perform simple request input validations
     * This is executed before middlewares and provides simple way of validating request input before doing anything else.
     *
     *
     * @param IInputValidator $validator
     * @return IInputValidator
     */
    public function validate(IInputValidator $validator): IInputValidator;
}