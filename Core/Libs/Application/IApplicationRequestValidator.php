<?php

namespace Core\Libs\Application;

use Core\CoreUtils\InputValidator\InputValidator;

/**
 * Implementing this interface into "IApplicationRequestHandler" will tell "Application" to execute "validate" method,
 * before any middlewares and handlers. If validation fails request is finished with status 400 and errors
 *
 * @package Core\Libs\Application
 */
interface IApplicationRequestValidator
{

    /**
     *
     * Validator is used to perform simple request input validations
     * This is executed before middlewares and provides simple way of validating request input before doing anything else.
     *
     *
     * @param InputValidator $validator
     * @return InputValidator
     */
    public function validate(InputValidator $validator): InputValidator;
}