<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/14/17
 * Time: 9:39 PM
 */

namespace Core\Libs\Application;

use Core\CoreUtils\Singleton;
use Core\CoreUtils\DataTransformer\Transformers\StringTransformer;
use Core\Exceptions\ApplicationException;
use Core\Libs\Database;
use Core\Libs\Request;
use Core\Libs\Response\IResponseStatus;
use Core\Libs\Response\Response;

class Application
{

    const DEFAULT_ACTION_IDENTIFIER = '_action';

    use Singleton;

    /** @var array */
    private $_config;

    /** @var string */
    private $_group = '';

    /** @var IApplicationActionHandler[]|IApplicationActionBeforeHandler[]|IApplicationActionAfterHandler[] */
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

        $db = $this->_config['database'];

        Database::getSharedInstance($db['host'], $db['dbname'], $db['username'], $db['password'], $db['port']);

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

        $identifier = $this->_request->query(
            $this->_config['application']['actionIdentifier'], null, StringTransformer::getSharedInstance()
        );

        if (false === isset($this->_actions[$identifier])) {

            $this->_response->status(404)->errors(['action' => "Action '{$identifier}' not found"])->end();

            return;
        }

        if (false === $this->_validateRequestMethod($this->_actions[$identifier])) {

            return;
        }

        if (false === $this->_executeBeforeHandler($this->_actions[$identifier])) {

            return;
        }

        $this->_actions[$identifier]->handle($this->_request, $this->_response);

        $this->_executeAfterHandler($this->_actions[$identifier]);

        $this->_response->end();
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
     *
     * Executes "before" handle if action handler implements correct interface
     *
     * @param null|IApplicationActionBeforeHandler $handler
     * @return bool
     */
    private function _executeBeforeHandler($handler): bool
    {

        if ($handler instanceof IApplicationActionBeforeHandler && false === $handler->before($this->_request, $this->_response)) {

            $this->_response->setError('_handle.before', 'Before action failed')->end();

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