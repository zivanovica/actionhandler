<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/19/17
 * Time: 1:14 PM
 */

namespace Core\Libs\Application;

use Core\CoreUtils\InputValidator\InputValidator;

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