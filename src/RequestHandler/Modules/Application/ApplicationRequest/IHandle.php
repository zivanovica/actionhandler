<?php

namespace RequestHandler\Modules\Application\ApplicationRequest;

use RequestHandler\Modules\Request\IRequest;
use RequestHandler\Modules\Response\IResponse;

/**
 *
 * Implementing this interface to some class will tell "Application" that this class is request handler and it contain "handle" method
 *
 * @package \RequestHandler\Modules\Application\ApplicationRequest
 */
interface IHandle
{

    /**
     *
     * Executes when related action is requested
     *
     * @param IRequest $request
     * @param IResponse $response
     * @return IResponse
     */
    public function handle(IRequest $request, IResponse $response): IResponse;
}
