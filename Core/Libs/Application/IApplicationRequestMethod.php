<?php

namespace Core\Libs\Application;

/**
 *
 * This interface allows you to get string identifier of each available request method with valid autocomplete
 *
 * @package Core\Libs\Application
 */
interface IApplicationRequestMethod
{

    const GET = 'GET';
    const POST = 'POST';
    const PUT = 'PUT';
    const PATCH = 'PATCH';
    const DELETE = 'DELETE';
}