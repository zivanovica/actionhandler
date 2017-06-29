<?php

namespace Core\Libs\Middleware;

use Core\CoreUtils\Singleton;
use Core\Exceptions\MiddlewareException;
use Core\Libs\Request\Request;
use Core\Libs\Response\Response;

/**
 *
 * This is used to add "IMiddleware" to current request middleware list
 *
 * @package Core\Libs\Middleware
 */
class Middleware
{

    use Singleton;

    /** @var array IMiddleware[] */
    private $_middlewares = [];

    /** @var array Used to store some data in middleware and used them later in request */
    private $_bag = [];

    /** @var Request */
    private $_request;

    /** @var Response */
    private $_response;

    /** @var bool */
    private $_finished = false;

    /**
     * @param Request $request
     * @param Response $response
     */
    private function __construct(Request $request, Response $response)
    {

        $this->_request = $request;
        $this->_response = $response;
    }

    /**
     *
     * Append another middleware in current execution list
     *
     * @param IMiddleware $middleware
     * @return Middleware
     */
    public function add(IMiddleware $middleware): Middleware
    {

        $this->_middlewares[] = $middleware;

        return $this;
    }

    /**
     *
     * Add identified data to bag
     *
     * @param string $identifier
     * @param $object
     * @return Middleware
     */
    public function put(string $identifier, $object): Middleware
    {

        $this->_bag[$identifier] = $object;

        return $this;
    }

    /**
     *
     * Retrieve value of given identifier from bag
     *
     * @param string $identifier
     * @param null $default
     * @return mixed|null
     */
    public function get(string $identifier, $default = null)
    {

        return isset($this->_bag[$identifier]) ? $this->_bag[$identifier] : $default;
    }

    /**
     *
     * Execute next middleware
     *
     * @throws MiddlewareException
     */
    public function next(): void
    {

        /** @var IMiddleware $middleware */
        $middleware = array_shift($this->_middlewares);

        if (null === $middleware && empty($this->_middlewares)) {

            $this->_finished = true;

            return;
        }

        if (false === $middleware instanceof IMiddleware) {

            throw new MiddlewareException(MiddlewareException::ERROR_INVALID_MIDDLEWARE, $middleware);
        }

        $middleware->run($this->_request, $this->_response, $this);
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