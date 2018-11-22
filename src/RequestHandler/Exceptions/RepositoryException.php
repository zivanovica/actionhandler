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
    const
        CLASS_NOT_FOUND = 110001,
        CLASS_TYPE_MISMATCH = 110002;

    protected $errors = [

        RepositoryException::CLASS_NOT_FOUND => 'Model class not found',
        RepositoryException::CLASS_TYPE_MISMATCH => 'Invalid class type'
    ];
}