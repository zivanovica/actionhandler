<?php
/**
 * Created by IntelliJ IDEA.
 * User: aleksandar
 * Date: 11/12/17
 * Time: 12:02 AM
 */

namespace RequestHandler\Exceptions;


use RequestHandler\Modules\Exception\BaseException;

class RepositoryException extends BaseException
{

    const ERR_CLASS_NOT_FOUND = 110001;
    const ERR_CLASS_TYPE_MISMATCH = 110002;

    protected $_errors = [

        RepositoryException::ERR_CLASS_NOT_FOUND => 'Model class not found',
        RepositoryException::ERR_CLASS_TYPE_MISMATCH => 'Invalid class type'
    ];
}