<?php

namespace RequestHandler\Modules\Middleware;

use RequestHandler\Modules\Request\IRequest;
use RequestHandler\Modules\Response\IResponse;

/**
 * Implement this interface to make your class an middleware that can be used in any request
 *
 * @package Core\Libs\Middleware
 */
interface IMiddlewareHandler
{

    /**
     *
     * Execute method for current middleware
     *
     * @param IRequest $request
     * @param IResponse $response
     * @param IMiddlewareContainer $middleware Used to call "next" method
     */
    public function handle(IRequest $request, IResponse $response, IMiddlewareContainer $middleware): void;
}