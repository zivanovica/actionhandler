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

    const ERR_BUILDER_OVERRIDE = 120001;

    protected $_errors = [

        BuilderException::ERR_BUILDER_OVERRIDE => 'Cannot override builder'
    ];
}