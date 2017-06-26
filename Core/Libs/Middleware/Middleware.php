<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/19/17
 * Time: 12:57 PM
 */

namespace Core\Libs\Middleware;


use Core\CoreUtils\Singleton;
use Core\Exceptions\MiddlewareException;
use Core\Libs\Request;
use Core\Libs\Response\Response;

class Middleware
{

    use Singleton;

    /** @var array IMiddleware[] */
    private $_middlewares = [];

    private $_bag = [];

    /** @var Request */
    private $_request;

    private $_response;

    private $_finished = false;

    private function __construct(Request $request, Response $response)
    {

        $this->_request = $request;

        $this->_response = $response;
    }

    public function add(IMiddleware $middleware): Middleware
    {

        $this->_middlewares[] = $middleware;

        return $this;
    }

    public function put(string $identifier, $object): Middleware
    {

        $this->_bag[$identifier] = $object;

        return $this;
    }

    public function get(string $identifier, $default = null)
    {

        return isset($this->_bag[$identifier]) ? $this->_bag[$identifier] : $default;
    }

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

    public function finished(): bool
    {

        return $this->_finished;
    }

}