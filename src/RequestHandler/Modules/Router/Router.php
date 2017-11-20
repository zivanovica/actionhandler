<?php

namespace RequestHandler\Modules\Router;

use RequestHandler\Modules\Application\ApplicationRequest\IHandle;
use RequestHandler\Modules\Application\IApplication;
use RequestHandler\Modules\Request\IRequestMethod;
use RequestHandler\Utils\ObjectFactory\ObjectFactory;

class Router implements IRouter
{


    /** @var array */
    private $_routes;

    /** @var IRoute */
    private $_currentRoute;

    private function __construct()
    {

        $this->_routes = [];
    }

    /**
     *
     * Register POST route
     *
     * @param string $route
     * @param string $handlerClass
     * @return IRouter
     */
    public function post(string $route, string $handlerClass): IRouter
    {

        $this->add($route, [IRequestMethod::POST], $handlerClass);

        return $this;
    }

    /**
     *
     * Register GET route
     *
     * @param string $route
     * @param string $handlerClass
     * @return IRouter
     */
    public function get(string $route, string $handlerClass): IRouter
    {

        $this->add($route, [IRequestMethod::GET], $handlerClass);

        return $this;
    }

    /**
     *
     * Register PUT route
     *
     * @param string $route
     * @param string $handlerClass
     * @return IRouter
     */
    public function put(string $route, string $handlerClass): IRouter
    {

        $this->add($route, [IRequestMethod::PUT], $handlerClass);

        return $this;
    }

    /**
     *
     * Register DELETE route
     *
     * @param string $route
     * @param string $handlerClass
     * @return IRouter
     */
    public function delete(string $route, string $handlerClass): IRouter
    {

        $this->add($route, [IRequestMethod::DELETE], $handlerClass);

        return $this;
    }

    /**
     *
     * Register PATCH route
     *
     * @param string $route
     * @param string $handlerClass
     * @return IRouter
     */
    public function patch(string $route, string $handlerClass): IRouter
    {

        $this->add($route, [IRequestMethod::PATCH], $handlerClass);

        return $this;
    }

    /**
     *
     * Register route on any request method
     *
     * @param string $route
     * @param string $handlerClass
     * @return IRouter
     */
    public function any(string $route, string $handlerClass): IRouter
    {

        $methods = [
            IRequestMethod::GET,
            IRequestMethod::POST,
            IRequestMethod::PUT,
            IRequestMethod::PATCH,
            IRequestMethod::DELETE
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
     * @return IRouter
     */
    public function add(string $route, array $methods, string $handlerClass): IRouter
    {

        list($regex, $parameters) = $this->_prepareRoute($route);

        $order = count($parameters);

        $subOrder = count(explode('/', $route));

        if (false === isset($this->_routes[$order][$subOrder])) {

            $this->_routes[$order][$subOrder] = [];
        }

        $this->_routes[$order][$subOrder][] = [$regex, $methods, $parameters, $handlerClass];

        return $this;
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

        if (null !== $this->_currentRoute) {

            return $this->_currentRoute;
        }

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

    /**
     *
     * Retrieve current route
     *
     * @return IRoute
     */
    public function currentRoute(): ?IRoute
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

            /** @var IRoute $routeInstance */
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

        $app = ObjectFactory::create(IApplication::class);

        return new class($app, $regex, $methods, $parameters, $handlerClass) implements IRoute
        {

            /** @var string Expression used to match route */
            private $_regex;

            /** @var array Acceptable request method for handler */
            private $_methods;

            /** @var array Route parameters */
            private $_parameters;

            /** @var IHandle */
            private $_handler;

            /** @var string */
            private $_handlerClass;

            /** @var IApplication */
            private $_application;

            public function __construct(IApplication $application, $regex, array $methods, array $parameters, string $handlerClass)
            {

                $this->_application = $application;

                $this->_regex = $regex;

                $this->_methods = $methods;

                $this->_handlerClass = $handlerClass;

                $this->_parameters = $parameters;
            }

            /**
             *
             * Retrieve route handler instance
             *
             * @return IHandle
             */
            public function handler(): IHandle
            {

                if (false === is_a($this->_handler, $this->_handlerClass)) {

                    $this->_handler = new $this->_handlerClass();
                }

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