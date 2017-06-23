<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/14/17
 * Time: 6:05 PM
 */

namespace Core\Libs\Response;


interface IResponseStatus
{
    const OK = 200;
    const CREATED = 201;
    const MULTIPLE_CHOICES = 300;
    const MOVED_PERMANENTLY = 301;
    const FOUND = 302;
    const NOT_MODIFIED = 304;
    const TEMP_REDIRECT = 307;
    const BAD_REQUEST = 400;
    const UNAUTHORIZED = 401;
    const FORBIDDEN = 403;
    const NOT_FOUND = 404;
    const METHOD_NOT_ALLOWED = 405;
    const CONFLICT = 409;
    const GONE = 410;
    const INTERNAL_ERROR = 500;
    const NOT_IMPLEMENTED = 501;
    const SERVICE_UNAVAILABLE = 503;
    const PERMISSION_DENIED = 550;
}