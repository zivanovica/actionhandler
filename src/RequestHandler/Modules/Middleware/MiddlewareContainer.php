<?php

namespace RequestHandler\Modules\Middleware;

use RequestHandler\Exceptions\MiddlewareException;
use RequestHandler\Modules\Request\IRequest;
use RequestHandler\Modules\Response\IResponse;
use RequestHandler\Modules\Response\Response;

/**
 *
 * This is used to add "IMiddleware" to current request middleware list
 *
 * @package Core\Libs\Middleware
 */
class MiddlewareContainer implements IMiddlewareContainer
{


    /** @var array IMiddleware[] */
    private $_middlewares = [];

    /** @var array Used to store some data in middleware and used them later in request */
    private $_bag = [];

    /** @var IRequest */
    private $_request;

    /** @var Response */
    private $_response;

    /** @var bool */
    private $_finished = false;

    /**
     * @param IRequest $request
     * @param IResponse $response
     */
    private function __construct(IRequest $request, IResponse $response)
    {

        $this->_request = $request;
        $this->_response = $response;
    }

    /**
     *
     * Append another middleware in current execution list
     *
     * @param IMiddlewareHandler $middleware
     * @return IMiddlewareContainer
     */
    public function add(IMiddlewareHandler $middleware): IMiddlewareContainer
    {

        $this->_middlewares[] = $middleware;

        return $this;
    }

    /**
     *
     * Execute next middleware
     *
     * @throws MiddlewareException
     */
    public function next(): void
    {

        /** @var IMiddlewareHandler $middleware */
        $middleware = array_shift($this->_middlewares);

        if (null === $middleware && empty($this->_middlewares)) {

            $this->_finished = true;

            return;
        }

        if (false === $middleware instanceof IMiddlewareHandler) {

            throw new MiddlewareException(MiddlewareException::ERR_BAD_MIDDLEWARE, $middleware);
        }

        $middleware->handle($this->_request, $this->_response, $this);
    }

    /**
     *
     * Determine did middleware ($this) executed all middlewares
     *
     * @return bool
     */
    public function finished(): bool
    {

        return $this->_finished;
    }

}