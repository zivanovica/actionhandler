<?php
/**
 * Created by IntelliJ IDEA.
 * User: coaps
 * Date: 4.11.2017.
 * Time: 01.17
 */

namespace RequestHandler\Exceptions;

use RequestHandler\Modules\Exception\BaseException;

class DispatcherException extends BaseException
{

    const ERR_CLASS_NOT_FOUND = 100001;
    const ERR_EVENT_TYPE_MISMATCH = 100002;
    const ERR_EVENT_NOT_FOUND = 100003;
    const ERR_BAD_EVENT_HANDLE = 100004;

    protected $_errors = [

        DispatcherException::ERR_CLASS_NOT_FOUND => 'Event class not found',
        DispatcherException::ERR_EVENT_TYPE_MISMATCH => 'Class does not implement ' . IListener::class,
        DispatcherException::ERR_EVENT_NOT_FOUND => 'Unknown event',
        DispatcherException::ERR_BAD_EVENT_HANDLE => 'Trying to prevent invalid event',
    ];
}