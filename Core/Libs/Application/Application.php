<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/14/17
 * Time: 9:39 PM
 */

namespace Core\Libs\Application;

use Core\CoreUtils\DataTransformer\Transformers\StringTransformer;
use Core\CoreUtils\Singleton;
use Core\Exceptions\ApplicationException;
use Core\Libs\Database;
use Core\Libs\Middleware\Middleware;
use Core\Libs\Request;
use Core\Libs\Response\IResponseStatus;
use Core\Libs\Response\Response;

class Application
{

    const DEFAULT_ACTION_IDENTIFIER = '_action';

    use Singleton;

    /** @var array */
    private $_config;

    private $_dbConfig;

    private $_appConfig;

    /** @var string */
    private $_group = '';

    /** @var IApplicationActionHandler[]|IApplicationActionValidator[]|IApplicationActionMiddleware|IApplicationActionAfterHandler[] */
    private $_actions;

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
     *
     * Register handlers for groped actions
     *
     * e.g
     *
     * [
     *  'user' => [
     *      'info' => new UserInfoHandler(), // -> action is "user.info"
     *      'profile' => [
     *          'create' => new CreateProfileHandler(), // -> action is "user.profile.create"
     *          ..... and so on
     *      ]
     *  ]
     * ]
     *
     * @param array $actions
     * @return Application
     */
    public function register(array $actions): Application
    {
        foreach ($actions as $action => $handler) {

            if (is_array($handler)) {

                $currentGroup = $this->_group;

                $this->_group = sprintf("%s%s", (empty($currentGroup) ? '' : "{$currentGroup}."), $action);

                $this->register($handler);

                $this->_group = $currentGroup;

                continue;

            }

            $this->_registerActionHandler($action, $handler);
        }

        return $this;
    }

    /**
     * Executes handler for requested action
     */
    public function run(): void
    {

        $identifier = $this->_request->query($this->_appConfig['actionIdentifier'], null, StringTransformer::getSharedInstance());

        if (false === $this->_validateRequestAction($identifier)) {

            return;
        }

        if (false === $this->_validateRequestMethod($this->_actions[$identifier])) {

            return;
        }

        if (false === $this->_executeMiddlewares($this->_actions[$identifier])) {

            return;
        }

        if (false === $this->_executeValidator($this->_actions[$identifier])) {

            return;
        }

        $this->_actions[$identifier]->handle($this->_request, $this->_response);

        $this->_executeAfterHandler($this->_actions[$identifier]);

        $this->_response->end();
    }

    /**
     *
     * Validates existence of given action
     *
     * @param string $actionIdentifier
     * @return bool
     */
    private function _validateRequestAction(string $actionIdentifier)
    {

        if (false === isset($this->_actions[$actionIdentifier])) {

            $this->_response->status(404)->errors(['action' => "Action '{$actionIdentifier}' not found"])->end();

            return false;
        }

        return true;
    }

    /**
     *
     * Validates request method for current action handler
     *
     * @param IApplicationActionHandler $handler
     * @return bool
     */
    private function _validateRequestMethod(IApplicationActionHandler $handler): bool
    {

        if (false === in_array($this->_request->method(), $handler->methods())) {

            $this->_response
                ->setError('method', 'Method not allowed')
                ->status(IResponseStatus::METHOD_NOT_ALLOWED)
                ->end();

            return false;
        }

        return true;
    }

    /**
     * @param null|IApplicationActionValidator $handler
     * @return bool
     */
    private function _executeValidator($handler): bool
    {

        if (false === $handler instanceof IApplicationActionValidator) {

            return true;
        }

        return $handler->validate($this->_request);

    }

    /**
     * @param null|IApplicationActionMiddleware $handler
     * @return bool
     */
    private function _executeMiddlewares($handler): bool
    {

        if (false === $handler instanceof IApplicationActionMiddleware) {

            return true;
        }

        $middleware = $handler->middleware(Middleware::getNewInstance($this->_request));

        $middleware->next();

        if (false === $middleware->finished()) {

            $this->_response->setError('_handle.middleware', 'Middlewares did not finished.')->end();

            return false;
        }


        return true;
    }

    /**
     *
     * Executes "after" handle if action handler implements correct interface
     *
     * @param null|IApplicationActionAfterHandler $handler
     * @return void
     */
    private function _executeAfterHandler($handler): void
    {

        if ($handler instanceof IApplicationActionAfterHandler) {

            $handler->after();
        }
    }

    /**
     *
     * Registers handler for given action in current group
     *
     * @param string $identifier
     * @param IApplicationActionHandler $handler
     * @return Application
     * @throws ApplicationException
     */
    private function _registerActionHandler(string $identifier, IApplicationActionHandler $handler): Application
    {

        $identifier = sprintf("%s%s", (empty($this->_group) ? '' : "{$this->_group}."), $identifier);

        if (isset($this->_actions[$identifier]) && $this->_actions[$identifier] instanceof IApplicationActionHandler) {

            throw new ApplicationException(ApplicationException::ERROR_DUPLICATE_ACTION_HANDLER, $identifier);
        }

        $this->_actions[$identifier] = $handler;

        return $this;
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

}