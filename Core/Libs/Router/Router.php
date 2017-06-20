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

class Router
{

    use Singleton;

    /** @var array */
    private $_routes;

    /** @var IRoute */
    private $_currentRoute;

    private function __construct()
    {
    }

    /**
     *
     * Register POST route
     *
     * @param string $route
     * @param IApplicationActionHandler $handler
     * @return Router
     */
    public function post(string $route, IApplicationActionHandler $handler): Router
    {

        $this->add($route, [IApplicationHandlerMethod::POST], $handler);

        return $this;
    }

    /**
     *
     * Register GET route
     *
     * @param string $route
     * @param IApplicationActionHandler $handler
     * @return Router
     */
    public function get(string $route, IApplicationActionHandler $handler): Router
    {

        $this->add($route, [IApplicationHandlerMethod::GET], $handler);

        return $this;
    }

    /**
     *
     * Register PUT route
     *
     * @param string $route
     * @param IApplicationActionHandler $handler
     * @return Router
     */
    public function put(string $route, IApplicationActionHandler $handler): Router
    {

        $this->add($route, [IApplicationHandlerMethod::PUT], $handler);

        return $this;
    }

    /**
     *
     * Register DELETE route
     *
     * @param string $route
     * @param IApplicationActionHandler $handler
     * @return Router
     */
    public function delete(string $route, IApplicationActionHandler $handler): Router
    {

        $this->add($route, [IApplicationHandlerMethod::DELETE], $handler);

        return $this;
    }

    /**
     *
     * Register PATCH route
     *
     * @param string $route
     * @param IApplicationActionHandler $handler
     * @return Router
     */
    public function patch(string $route, IApplicationActionHandler $handler): Router
    {

        $this->add($route, [IApplicationHandlerMethod::PATCH], $handler);

        return $this;
    }

    /**
     *
     * Register route on any request method
     *
     * @param string $route
     * @param IApplicationActionHandler $handler
     * @return Router
     */
    public function any(string $route, IApplicationActionHandler $handler): Router
    {

        $methods = [
            IApplicationHandlerMethod::GET,
            IApplicationHandlerMethod::POST,
            IApplicationHandlerMethod::PUT,
            IApplicationHandlerMethod::PATCH,
            IApplicationHandlerMethod::DELETE
        ];

        $this->add($route, $methods, $handler);

        return $this;

    }

    /**
     *
     * Register route for all given methods
     *
     * @param string $route
     * @param array $methods
     * @param IApplicationActionHandler $handler
     */
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

    public function route(string $method, string $route): ?IRoute
    {

        ksort($this->_routes);

        foreach ($this->_routes as $routes) {

            krsort($routes);

            foreach ($routes as $routeData) {

                $this->_currentRoute = $this->_route($method, $route, $routeData);

                if (null !== $this->_currentRoute) {

                    return $this->_currentRoute;
                }
            }
        }

        return null;
    }

    public function currentRoute(): IRoute
    {

        return $this->_currentRoute;
    }


    private function _route(string $method, string $route, array $routes): ?IRoute
    {

        foreach ($routes as $routeData) {

            $routeInstance = call_user_func_array([$this, '_getIRouteInstance'], $routeData);

            if (false === $routeInstance->valid($method, $route)) {

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

                $this->_parameters = $parameters;
            }

            public function handler(): IApplicationActionHandler
            {
                return $this->_handler;
            }

            public function parameters(): array
            {
                return $this->_parameters;
            }

            public function valid(string $method, string $route): bool
            {

                if (false === in_array($method, $this->_methods)) {

                    return false;
                }

                $matches = [];

                $found = (bool)preg_match_all($this->_regex, $route, $matches);

                if (false === $found) {

                    return false;
                }

                $parameters = $this->_parameters;

                $this->_parameters = [];

                array_shift($matches);

                if (false === empty($matches)) {

                    foreach ($parameters as $offset => $parameter) {

                        $this->_parameters[$parameter] = isset($matches[$offset][0]) ? $matches[$offset][0] : null;
                    }
                }

                return true;
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