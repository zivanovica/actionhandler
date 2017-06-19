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

class Middleware
{

    use Singleton;

    /** @var array IMiddleware[] */
    private $_middlewares = [];

    /** @var Request */
    private $_request;

    private function __construct(Request $request)
    {

        $this->_request = $request;
    }

    public function add(IMiddleware $middleware): Middleware
    {
        
        $this->_middlewares[] = $middleware;
        
        return $this;
    }
    
    public function next(): void
    {

        /** @var IMiddleware $middleware */
        $middleware = array_shift($this->_middlewares);

        if (null === $middleware && empty($this->_middlewares)) {

            return;
        }

        if (false === $middleware instanceof IMiddleware) {

            throw new MiddlewareException(MiddlewareException::ERROR_INVALID_MIDDLEWARE, $middleware);
        }

        $middleware->run($this->_request, $this);
    }

    public function finished(): bool
    {

        return empty($this->_middlewares);
    }

}