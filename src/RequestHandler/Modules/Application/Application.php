<?php

namespace RequestHandler\Modules\Application;

use RequestHandler\Exceptions\ApplicationException;
use RequestHandler\Modules\Application\ApplicationRequest\IFilter;
use RequestHandler\Modules\Application\ApplicationRequest\IHandle;
use RequestHandler\Modules\Application\ApplicationRequest\IMiddleware;
use RequestHandler\Modules\Application\ApplicationRequest\IValidate;
use RequestHandler\Modules\Database\IDatabase;
use RequestHandler\Modules\Event\IDispatcher;
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

    const DEFAULT_ACTION_IDENTIFIER = 'action';

    /** @var array */
    private $config;

    /** @var array */
    private $dbConfig;

    /** @var array */
    private $appConfig;

    /** @var IRequest */
    private $request;

    /** @var Response */
    private $response;

    /** @var IRouter */
    private $router;

    /** @var IDispatcher */
    private $dispatcher;

    /** @var array */
    private $attributes;

    /**
     * @param string $configPath Path to configuration file
     * @param IRouter $router
     * @param IRequest $request
     * @param IResponse $response
     * @param IDispatcher $dispatcher
     * @throws ApplicationException
     */
    private function __construct(
        string $configPath,
        IRouter $router,
        IRequest $request,
        IResponse $response,
        IDispatcher $dispatcher
    )
    {

        if (false === $this->loadConfig($configPath)) {
            throw new ApplicationException(ApplicationException::BAD_CONFIG);
        }

        [
            'application' => $this->appConfig, 'database' => $this->dbConfig
            ] = $this->config;

        $this->appConfig['debug'] = $this->appConfig['debug'] ?? false;

        [
            $this->router, $this->request, $this->response, $this->dispatcher
            ] = [$router, $request, $response, $dispatcher];

        $this->attributes = new \RequestHandler\Utils\Collection\Hash\Hash(\string::class, \object::class);

        $this->setAttribute('config', $this->config);
    }

    /**
     *
     * Retrieve database configuration
     *
     * @return array
     */
    public function config(): array
    {
        return $this->config;
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
        $this->attributes[$name] = $value;

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
        $attribute = isset($this->attributes[$name]) ? $this->attributes[$name] : $default;

        return null === $filter ? $attribute : $filter->filter($attribute);
    }

    /**
     *
     * Executes handler for requested action
     *
     * @param \Closure $routeRegisterCallback
     * @throws \ReflectionException
     * @throws \Throwable
     *
     */
    public function boot(\Closure $routeRegisterCallback): void
    {
        $routeRegisterCallback($this->router);

        ignore_user_abort(true);

        ObjectFactory::create(IDatabase::class);

        try {
            $this->execute($this->router);
        } catch (\Throwable $exception) {
            if ($this->appConfig['debug'] ?? false) {
                throw $exception;
            }

            $this->response
                ->status(IResponseStatus::INTERNAL_ERROR)
                ->errors([
                  'message' => 'There were some errors',
                  'code' => $exception->getCode(),
                  'exception' => get_class($exception)
            ]);
        }

        $this->finishRequest();
    }

    /**
     * @param null|IFilter $filter
     * @throws \ReflectionException
     */
    private function setRequestFilter(?IFilter $filter): void
    {
        if (null === $filter) {
            return;
        }

        $this->request->setFilter($filter->filter(ObjectFactory::create(IRequestFilter::class)));
    }

    /**
     * @param IRouter $router
     * @throws \ReflectionException
     */
    private function execute(IRouter $router)
    {
        $route = $this->request->query($this->appConfig['actionIdentifier'], '/');

        $routeHandle = $router->route($this->request->method(), $route);

        if (false === $routeHandle instanceof IRoute) {
            throw new ApplicationException(ApplicationException::INVALID_ROUTE, $route);
        }

        $handler = $routeHandle->handler();

        if (false === $handler instanceof IHandle) {
            throw new ApplicationException(ApplicationException::BAD_REQUEST_HANDLER, get_class($handler));
        }

        if (
            $this->executeValidator($handler instanceof IValidate ? $handler : null) &&
            $this->executeMiddleware($handler instanceof IMiddleware ? $handler : null)
        ) {
            $this->setRequestFilter($handler instanceof IFilter ? $handler : null);

            $this->response = $handler->handle($this->request, $this->response);
        }
    }

    /**
     * @param $handler
     * @return bool
     * @throws \ReflectionException
     */
    private function executeValidator(?IValidate $handler): bool
    {

        if (null === $handler) {
            return true;
        }

        /** @var IInputValidator $validator */
        $validator = ObjectFactory::create(IInputValidator::class);

        $validator->setFields($this->request->getAll());

        $handler->validate($validator);

        if ($validator->hasErrors()) {



            $this->response
                ->status(IResponseStatus::BAD_REQUEST)
                ->errors($validator->getErrors())
                ->addError('request.validate', 'Action did not pass validation.');

            return false;
        }

        return true;
    }

    /**
     * @param $handler
     * @return bool
     * @throws \ReflectionException
     */
    private function executeMiddleware(?IMiddleware $handler): bool
    {
        if (null === $handler) {
            return true;
        }

        /** @var IMiddlewareContainer $middleware */
        $middleware = ObjectFactory::create(IMiddlewareContainer::class, $this->request, $this->response);

        $handler->middleware($middleware);

        $middleware->next();

        if (false === $middleware->finished()) {
            $this->response->addError('request.middleware', 'Middlewares did not finished.');

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
    private function loadConfig(string $configPath): bool
    {

        if (false === is_readable($configPath)) {
            return false;
        }

        $json = file_get_contents($configPath);

        $this->config = json_decode($json, true);

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
    private function finishRequest(): void
    {
        $response = $this->response;

        foreach ($response->getHeaders() as $header => $value) {
            header("{$header}: {$value}");
        }

        header("Content-Type: {$response->getContentType()}", true, $response->getStatus());

        echo $response->getOutput();

        if (function_exists('fastcgi_finish_request')) {
            fastcgi_finish_request();
        }

        $this->dispatcher->fire();
    }

}
