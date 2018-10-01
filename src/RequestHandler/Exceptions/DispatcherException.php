<?php
/**
 * Created by IntelliJ IDEA.
 * User: coaps
 * Date: 4.11.2017.
 * Time: 01.17
 */

namespace RequestHandler\Exceptions;

use RequestHandler\Modules\Event\IEvent;
use RequestHandler\Modules\Exception\BaseException;

class DispatcherException extends BaseException
{
    const
        CLASS_NOT_FOUND = 100001,
        EVENT_TYPE_MISMATCH = 100002,
        EVENT_NOT_FOUND = 100003,
        BAD_EVENT_HANDLE = 100004;

    protected $_errors = [
        DispatcherException::CLASS_NOT_FOUND => 'Event class not found',
        DispatcherException::EVENT_TYPE_MISMATCH => 'Class does not implement ' . IEvent::class,
        DispatcherException::EVENT_NOT_FOUND => 'Unknown event',
        DispatcherException::BAD_EVENT_HANDLE => 'Trying to prevent invalid event',
    ];
}