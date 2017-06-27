<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/20/17
 * Time: 3:00 PM
 */

namespace Core\Libs\Router;

use Core\CoreUtils\Singleton;
use Core\Libs\Application\IApplicationRequestHandler;
use Core\Libs\Application\IApplicationRequestMethod;

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
     * @param string $handlerClass
     * @return Router
     */
    public function post(string $route, string $handlerClass): Router
    {

        $this->add($route, [IApplicationRequestMethod::POST], $handlerClass);

        return $this;
    }

    /**
     *
     * Register GET route
     *
     * @param string $route
     * @param string $handlerClass
     * @return Router
     */
    public function get(string $route, string $handlerClass): Router
    {

        $this->add($route, [IApplicationRequestMethod::GET], $handlerClass);

        return $this;
    }

    /**
     *
     * Register PUT route
     *
     * @param string $route
     * @param string $handlerClass
     * @return Router
     */
    public function put(string $route, string $handlerClass): Router
    {

        $this->add($route, [IApplicationRequestMethod::PUT], $handlerClass);

        return $this;
    }

    /**
     *
     * Register DELETE route
     *
     * @param string $route
     * @param string $handlerClass
     * @return Router
     */
    public function delete(string $route, string $handlerClass): Router
    {

        $this->add($route, [IApplicationRequestMethod::DELETE], $handlerClass);

        return $this;
    }

    /**
     *
     * Register PATCH route
     *
     * @param string $route
     * @param string $handlerClass
     * @return Router
     */
    public function patch(string $route, string $handlerClass): Router
    {

        $this->add($route, [IApplicationRequestMethod::PATCH], $handlerClass);

        return $this;
    }

    /**
     *
     * Register route on any request method
     *
     * @param string $route
     * @param string $handlerClass
     * @return Router
     */
    public function any(string $route, string $handlerClass): Router
    {

        $methods = [
            IApplicationRequestMethod::GET,
            IApplicationRequestMethod::POST,
            IApplicationRequestMethod::PUT,
            IApplicationRequestMethod::PATCH,
            IApplicationRequestMethod::DELETE
        ];

        $this->add($route, $methods, $handlerClass);

        return $this;

    }

    /**
     *
     * Register route for all given methods
     *
     * @param string $route
     * @param array $methods
     * @param string $handlerClass
     */
    public function add(string $route, array $methods, string $handlerClass): void
    {

        list($regex, $parameters) = $this->_prepareRoute($route);

        $order = count($parameters);

        $subOrder = count(explode('/', $route));

        if (false === isset($this->_routes[$order][$subOrder])) {

            $this->_routes[$order][$subOrder] = [];
        }

        $this->_routes[$order][$subOrder][] = [$regex, $methods, $parameters, $handlerClass];
    }

    /**
     *
     * Decides and retrieves corresponding IRoute for current request
     *
     * @param string $method
     * @param string $route
     * @return IRoute|null
     */
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


    /**
     *
     * Retrieves valid IRoute for current request
     *
     * @param string $method
     * @param string $route
     * @param array $routes
     * @return IRoute|null
     */
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

    /**
     *
     * Creates instance of IRoute
     *
     * @param string $regex Route regular expression
     * @param array $methods Allowed route methods
     * @param array $parameters Dynamic route parameters
     * @param string $handlerClass Route handler class name
     * @return IRoute
     */
    private function _getIRouteInstance($regex, array $methods, array $parameters, string $handlerClass): IRoute
    {
        return new class($regex, $methods, $parameters, $handlerClass) implements IRoute
        {

            /** @var string Expression used to match route */
            private $_regex;

            /** @var array Acceptable request method for handler */
            private $_methods;

            /** @var array Route parameters */
            private $_parameters;

            /** @var IApplicationRequestHandler */
            private $_handler;

            public function __construct($regex, array $methods, array $parameters, string $handlerClass)
            {

                $this->_regex = $regex;

                $this->_methods = $methods;

                $this->_handler = new $handlerClass();

                $this->_parameters = $parameters;
            }

            /**
             *
             * Retrieve route handler instance
             *
             * @return IApplicationRequestHandler
             */
            public function handler(): IApplicationRequestHandler
            {
                return $this->_handler;
            }

            /**
             *
             * Retrieve all route parameters
             *
             * @return array
             */
            public function parameters(): array
            {
                return $this->_parameters;
            }

            /**
             *
             * Validates method and given string to current route
             *
             * @param string $method
             * @param string $route
             * @return bool
             */
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

    /**
     *
     * Parse given route, creates regex and array of route parameters
     *
     * @param string $route
     * @return array
     */
    private function _prepareRoute(string $route): array
    {

        $matches = [];

        preg_match_all('/\:(?P<parameters>[a-zA-Z\_\-]+)/', $route, $matches);

        $regex = preg_replace('/(\:[a-zA-Z\_\-]+)/', "(.+)", $route);

        $parameters = isset($matches['parameters']) ? $matches['parameters'] : [];

        unset($matches);

        return ['/^' . str_replace(['/'], ['\/'], $regex) . '\/?$/', $parameters];
    }
}