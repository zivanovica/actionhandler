<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/15/17
 * Time: 4:55 PM
 */

namespace Core\Libs\Application;


interface IApplicationHandlerMethod
{
    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const PATCH = 'PATCH';
    const DELETE = 'DELETE';
}