<?php
/**
 * Created by IntelliJ IDEA.
 * User: aleksandar
 * Date: 11/20/17
 * Time: 9:35 PM
 */

namespace RequestHandler\Exceptions;


use RequestHandler\Modules\Exception\BaseException;

class BuilderException extends BaseException
{

    const BUILDER_OVERRIDE = 120001;

    protected $errors = [

        BuilderException::BUILDER_OVERRIDE => 'Cannot override builder'
    ];
}