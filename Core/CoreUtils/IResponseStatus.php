<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/14/17
 * Time: 6:05 PM
 */

namespace Core\CoreUtils;


interface IResponseStatus
{
    const STATUS_OK = 200;
    const STATUS_MULTIPLE_CHOICES = 300;
    const STATUS_MOVED_PERMANENTLY = 301;
    const STATUS_FOUND = 302;
    const STATUS_NOT_MODIFIED = 304;
    const STATUS_TEMP_REDIRECT = 307;
    const STATUS_BAD_REQUEST = 400;
    const STATUS_UNAUTHORIZED = 401;
    const STATUS_FORBIDDEN = 403;
    const STATUS_NOT_FOUND = 404;
    const STATUS_GONE = 410;
    const STATUS_INTERNAL_ERROR = 500;
    const STATUS_NOT_IMPLEMENTED = 501;
    const STATUS_SERVICE_UNAVAILABLE = 503;
    const STATUS_PERMISSION_DENIED = 550;
}