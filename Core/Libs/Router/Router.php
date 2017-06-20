<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/20/17
 * Time: 3:00 PM
 */

namespace Core\Libs\Router;


use Core\CoreUtils\Singleton;
use Core\Libs\Application\IApplicationActionHandler;
use Core\Libs\Application\IApplicationHandlerMethod;
use Core\Libs\Request;

class Router
{

    use Singleton;

    /** @var IRoute[] */
    private $_routes;

    private function __construct()
    {
    }

    public function post(string $route, IApplicationActionHandler $handler): Router
    {

        $this->add($route, [IApplicationHandlerMethod::POST], $handler);

        return $this;
    }

    public function get(string $route, IApplicationActionHandler $handler): Router
    {

        $this->add($route, [IApplicationHandlerMethod::GET], $handler);

        return $this;
    }

    public function put(string $route, IApplicationActionHandler $handler): Router
    {

        $this->add($route, [IApplicationHandlerMethod::PUT], $handler);

        return $this;
    }

    public function delete(string $route, IApplicationActionHandler $handler): Router
    {

        $this->add($route, [IApplicationHandlerMethod::DELETE], $handler);

        return $this;
    }

    public function patch(string $route, IApplicationActionHandler $handler): Router
    {

        $this->add($route, [IApplicationHandlerMethod::PATCH], $handler);

        return $this;
    }

    public function add(string $route, array $methods, IApplicationActionHandler $handler): void
    {

        list($regex, $parameters) = $this->_parseRoute($route);

        $order = count($parameters);

        $subOrder = count(explode('/', $route));

        if (false === isset($this->_routes[$order][$subOrder])) {

            $this->_routes[$order][$subOrder] = [];
        }

        $this->_routes[$order][$subOrder][] = [$regex, $methods, $parameters, $handler];
    }

    public function route(string $route): ?IRoute
    {

        ksort($this->_routes);

        foreach ($this->_routes as $routes) {

            krsort($routes);

            foreach ($routes as $routeData) {

                /** @var IRoute $routeInstance */
                $routeInstance = $this->_route($route, $routeData);

                if (null !== $routeInstance) {

                    return $routeInstance;
                }
            }
        }

        return null;
    }

    private function _route(string $route, array $routes): ?IRoute
    {

        foreach ($routes as $routeData) {

            $routeInstance = call_user_func_array([$this, '_getIRouteInstance'], $routeData);

            if (false === $routeInstance->valid($route)) {

                unset($routeInstance);

                continue;
            }

            return $routeInstance;
        }

        return null;
    }

    private function _getIRouteInstance($regex, array $methods, array $parameters, IApplicationActionHandler $handler): IRoute
    {
        return new class($regex, $methods, $parameters, $handler) implements IRoute
        {

            private $_regex;

            private $_methods;

            private $_parameters;

            private $_handler;

            public function __construct($regex, array $methods, array $parameters, IApplicationActionHandler $handler)
            {

                $this->_regex = $regex;

                $this->_methods = $methods;

                $this->_handler = $handler;

                $this->_parameters;
            }

            public function handler(): IApplicationActionHandler
            {
                return $this->_handler;
            }

            public function valid(string $route): bool
            {
                return (bool)preg_match($this->_regex, $route) && in_array(Request::getSharedInstance()->method(), $this->_methods);
            }
        };
    }

    private function _parseRoute(string $route): array
    {

        $matches = [];

        preg_match_all('/\:(?P<parameters>[a-zA-Z\_\-]+)/', $route, $matches);

        $regex = preg_replace('/(\:[a-zA-Z\_\-]+)/', "(.+)", $route);

        $parameters = isset($matches['parameters']) ? $matches['parameters'] : [];

        unset($matches);

        return ['/^' . str_replace(['/'], ['\/'], $regex) . '\/?$/', $parameters];
    }
}