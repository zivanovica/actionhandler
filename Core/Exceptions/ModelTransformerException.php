<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/21/17
 * Time: 3:19 PM
 */

namespace Core\Exceptions;

class ModelTransformerException extends AFrameworkException
{

    const ERROR_INVALID_MODEL_CLASS = 60001;

    protected $_errors = [
        ModelTransformerException::ERROR_INVALID_MODEL_CLASS => 'Class doesn\'t extends Model'
    ];

}