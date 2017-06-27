<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/14/17
 * Time: 9:39 PM
 */

namespace Core\Libs\Application;

use Core\CoreUtils\DataTransformer\Transformers\RouteTransformer;
use Core\CoreUtils\DataTransformer\Transformers\StringTransformer;
use Core\CoreUtils\DataTransformer\Transformers\WaterfallTransformer;
use Core\CoreUtils\InputValidator\InputValidator;
use Core\CoreUtils\Singleton;
use Core\Exceptions\ApplicationException;
use Core\Libs\Database;
use Core\Libs\Middleware\Middleware;
use Core\Libs\Request;
use Core\Libs\Response\IResponseStatus;
use Core\Libs\Response\Response;
use Core\Libs\Router\Router;

class Application
{

    const DEFAULT_ACTION_IDENTIFIER = '_action';

    use Singleton;

    /** @var array */
    private $_config;

    /** @var array */
    private $_dbConfig;

    /** @var array */
    private $_appConfig;

    /** @var Request */
    private $_request;

    /** @var Response */
    private $_response;

    /**
     * @param string $configPath Path to configuration file
     * @throws ApplicationException
     */
    private function __construct(string $configPath)
    {

        if (false === $this->_loadConfig($configPath)) {

            throw new ApplicationException(ApplicationException::ERROR_INVALID_CONFIG);
        }

        $this->_appConfig = $this->_config['application'];

        $this->_dbConfig = $this->_config['database'];

        Database::getSharedInstance(
            $this->_dbConfig['host'],
            $this->_dbConfig['dbname'],
            $this->_dbConfig['username'],
            $this->_dbConfig['password'],
            $this->_dbConfig['port']
        );

        $this->_request = Request::getSharedInstance();

        $this->_response = Response::getSharedInstance();

    }

    /**
     * Executes handler for requested action
     *
     * @param Router $router
     */
    public function run(Router $router): void
    {

        $requestRoute = $this->_request->query(
            $this->_appConfig['actionIdentifier'], Application::DEFAULT_ACTION_IDENTIFIER, StringTransformer::getSharedInstance()
        );

        $route = $router->route($this->_request->method(), $requestRoute);

        if (null === $route) {

            $this->_sendResponse($this->_response->status(404)->errors(['action' => "Action '{$requestRoute}' not found"]));

            return;
        }

        if (false === $this->_executeValidator($route->handler())) {

            return;
        }

        if (false === $this->_executeMiddlewares($route->handler())) {

            return;
        }

        $response = $route->handler()->handle($this->_request, $this->_response);

        $this->_executeAfterHandler($route->handler());

        $this->_sendResponse($response);
    }

    /**
     * @param null|IApplicationRequestValidator $handler
     * @return bool
     */
    private function _executeValidator($handler): bool
    {

        if (false === $handler instanceof IApplicationRequestValidator) {

            return true;
        }

        $input = array_merge($this->_request->allParameters(), $this->_request->allQuery(), $this->_request->allData());

        $validator = $handler->validate(InputValidator::getNewInstance($input));

        if ($validator->hasErrors()) {

            $this->_sendResponse(
                $this->_response
                    ->errors($validator->getErrors())
                    ->addError('_handle.validate', 'Action did not pass validation.')
            );

            return false;
        }

        return true;
    }

    /**
     * @param null|IApplicationRequestMiddleware $handler
     * @return bool
     */
    private function _executeMiddlewares($handler): bool
    {

        if (false === $handler instanceof IApplicationRequestMiddleware) {

            return true;
        }

        $middleware = $handler->middleware(Middleware::getSharedInstance($this->_request, $this->_response));

        $middleware->next();

        if (false === $middleware->finished()) {

            $this->_sendResponse($this->_response->addError('_handle.middleware', 'Middlewares did not finished.'));

            return false;
        }

        return true;
    }

    /**
     *
     * Executes "after" handle if action handler implements correct interface
     *
     * @param null|IApplicationRequestAfterHandler $handler
     * @return void
     */
    private function _executeAfterHandler($handler): void
    {

        if ($handler instanceof IApplicationRequestAfterHandler) {

            $handler->after();
        }
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

    private function _sendResponse(Response $response): void
    {

        http_response_code($response->getStatus());

        echo json_encode([
            'status' => $response->hasErrors() ? Response::STATUS_ERROR : Response::STATUS_SUCCESS,
            'data' => $response->hasErrors() ? $response->getErrors() : $response->getData()
        ]);

        return;
    }

}