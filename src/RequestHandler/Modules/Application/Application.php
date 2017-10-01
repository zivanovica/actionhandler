<?php

namespace RequestHandler\Modules\Application;

use RequestHandler\Exceptions\ApplicationException;
use RequestHandler\Modules\Application\ApplicationRequest\IFilter;
use RequestHandler\Modules\Application\ApplicationRequest\IMiddleware;
use RequestHandler\Modules\Application\ApplicationRequest\IValidate;
use RequestHandler\Modules\Database\IDatabase;
use RequestHandler\Modules\Middleware\IMiddlewareContainer;
use RequestHandler\Modules\Request\IRequest;
use RequestHandler\Modules\Request\RequestFilter\IRequestFilter;
use RequestHandler\Modules\Response\IResponse;
use RequestHandler\Modules\Response\IResponseStatus;
use RequestHandler\Modules\Response\Response;
use RequestHandler\Modules\Router\IRoute;
use RequestHandler\Modules\Router\IRouter;
use RequestHandler\Utils\DataFilter\IDataFilter;
use RequestHandler\Utils\InputValidator\IInputValidator;
use RequestHandler\Utils\ObjectFactory\ObjectFactory;

/**
 *
 * This is core of framework, its where everything gets connected and executed
 *
 * Application is used to register routes with handlers, execute route corresponding to url
 *
 * @package Core\Libs\Application
 */
class Application implements IApplication
{

    const DEFAULT_ACTION_IDENTIFIER = '_action';

    /** @var array */
    private $_config;

    /** @var array */
    private $_dbConfig;

    /** @var array */
    private $_appConfig;

    /** @var IRequest */
    private $_request;

    /** @var Response */
    private $_response;

    /** @var array */
    private $_attributes;

    /**
     * @param string $configPath Path to configuration file
     * @param array $attributes
     * @param IRequest $request
     * @param IResponse $response
     * @throws ApplicationException
     */
    private function __construct(string $configPath, array $attributes = [], IRequest $request, IResponse $response)
    {

        if (false === $this->_loadConfig($configPath)) {

            throw new ApplicationException(ApplicationException::ERROR_INVALID_CONFIG);
        }

        $this->_appConfig = $this->_config['application'];

        $this->_dbConfig = $this->_config['database'];

        ObjectFactory::create(
            IDatabase::class,
            $this->_dbConfig['host'],
            $this->_dbConfig['dbname'],
            $this->_dbConfig['username'],
            $this->_dbConfig['password'],
            $this->_dbConfig['port']
        );

        $this->_request = $request;

        $this->_response = $response;

        $this->_appConfig['debug'] = false === isset($this->_appConfig['debug']) ? false : $this->_appConfig['debug'];

        $this->_attributes = array_merge(['config' => $this->_config], $attributes);
    }

    /**
     *
     * Sets attribute value
     *
     * @param string $name
     * @param mixed $value
     * @return IApplication
     */
    public function setAttribute(string $name, $value): IApplication
    {

        $this->_attributes[$name] = $value;

        return $this;
    }

    /**
     *
     * Retrieve attribute value
     *
     * @param string $name
     * @param mixed $default
     * @param null|IDataFilter $filter
     * @return mixed|null
     */
    public function getAttribute(string $name, $default = null, ?IDataFilter $filter = null)
    {

        $attribute = isset($this->_attributes[$name]) ? $this->_attributes[$name] : $default;

        return null === $filter ? $attribute : $filter->filter($attribute);
    }

    /**
     * Executes handler for requested action
     *
     * @param IRouter $router
     * @throws \Throwable
     */
    public function run(IRouter $router): void
    {


        if (false === $this->_appConfig['debug']) {
            try {

                $this->_execute($router);
            } catch (\Throwable $exception) {

                $this->_response->status(500)->errors([
                    'message' => 'There were some errors',
                    'code' => $exception->getCode(),
                    'exception' => get_class($exception)
                ]);

                $this->_finishRequest();
            }

            return;
        }

        $this->_execute($router);

        return;
    }

    /**
     * @param null|IFilter $handler
     * @return void
     */
    private function _setRequestFilter($handler): void
    {

        if (false === $handler instanceof IFilter) {

            return;
        }

        $this->_request->setFilter($handler->filter(ObjectFactory::create(IRequestFilter::class)));
    }

    /**
     *
     * Validates current url and if it is okay return IRoute associated to it
     *
     * @param IRouter $router
     * @return IRoute|null
     */
    private function _validateAndGetRoute(IRouter $router): ?IRoute
    {

        $requestRoute = $this->_request->query($this->_appConfig['actionIdentifier'], '/');

        $route = $router->route($this->_request->method(), $requestRoute);

        if (null === $route) {

            $this->_response->status(404)->errors(['action' => "Route '{$requestRoute}' not found"]);

            $this->_finishRequest();

            return null;
        }

        return $route;
    }

    /**
     *
     * Executes everything
     *
     * @param IRouter $router
     */
    private function _execute(IRouter $router)
    {
        $route = $this->_validateAndGetRoute($router);

        if (false === $route instanceof IRoute)
            return;

        $handler = $route->handler();

        if ($handler instanceof IApplicationAware)
            $handler->onApplication($this);

        if (false === $this->_executeValidator($handler))
            return;

        if (false === $this->_executeMiddlewares($handler))
            return;

        $this->_setRequestFilter($handler);

        $this->_response = $handler->handle($this->_request, $this->_response);

        $this->_finishRequest();
    }

    /**
     * @param null|IValidate $handler
     * @return bool
     */
    private function _executeValidator($handler): bool
    {

        if (false === $handler instanceof IValidate) {

            return true;
        }

        /** @var IInputValidator $validator */
        $validator = ObjectFactory::create(IInputValidator::class);

        $validator->setFields($this->_request->getAll());

        $handler->validate($validator);

        if ($validator->hasErrors()) {

            $this->_response
                ->status(IResponseStatus::BAD_REQUEST)
                ->errors($validator->getErrors())
                ->addError('_request.validate', 'Action did not pass validation.');

            $this->_finishRequest();

            return false;
        }

        return true;
    }

    /**
     * @param null|IMiddleware $handler
     * @return bool
     */
    private function _executeMiddlewares($handler): bool
    {

        if (false === $handler instanceof IMiddleware) {

            return true;
        }

        /** @var IMiddlewareContainer $middleware */
        $middleware = ObjectFactory::create(IMiddlewareContainer::class, $this->_request, $this->_response);

        $handler->middleware($middleware);

        $middleware->next();

        if (false === $middleware->finished()) {

            $this->_response->addError('_request.middleware', 'Middlewares did not finished.');

            $this->_finishRequest();

            return false;
        }

        return true;
    }

    /**
     *
     * Stores json information from given config file in config variable
     *
     * @param string $configPath
     * @return bool
     */
    private function _loadConfig(string $configPath): bool
    {

        if (false === is_readable($configPath)) {

            return false;
        }

        $json = file_get_contents($configPath);

        $this->_config = json_decode($json, true);

        if (JSON_ERROR_NONE !== json_last_error()) {

            return false;
        }

        return true;
    }

    /**
     *
     * Will finish request with proper data/errors and status code
     *
     */
    private function _finishRequest(): void
    {

        $response = $this->_response;

        http_response_code($response->getStatus());

        echo json_encode([
            'status' => $response->hasErrors() ? Response::STATUS_ERROR : Response::STATUS_SUCCESS,
            'data' => $response->hasErrors() ? $response->getErrors() : $response->getData()
        ]);

        return;
    }

}