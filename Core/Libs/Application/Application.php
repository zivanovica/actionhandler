<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/14/17
 * Time: 9:39 PM
 */

namespace Core\Libs\Application;

use Core\CoreUtils\Singleton;
use Core\CoreUtils\ValueTransformer\Transformers\StringTransformer;
use Core\Exceptions\ApplicationException;
use Core\Libs\Database;
use Core\Libs\Request;
use Core\Libs\Response;

class Application
{

    const DEFAULT_ACTION_IDENTIFIER = '_action';

    use Singleton;

    /** @var string */
    private $_group = '';

    /** @var string */
    private $_actionIdentifier;

    /** @var array */
    private $_actions;

    /** @var Request */
    private $_request;

    /** @var Response */
    private $_response;

    private function __construct(string $actionIdentifier = Application::DEFAULT_ACTION_IDENTIFIER)
    {

        $this->_request = Request::getSharedInstance();

        $this->_response = Response::getSharedInstance();

        $this->_actionIdentifier = $actionIdentifier;

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

            } else if ($handler instanceof IApplicationHandler) {

                $this->_action($action, $handler);
            }
        }

        return $this;
    }

    /**
     * Executes handler for requested action
     */
    public function run(): void
    {

        Database::getSharedInstance('127.0.0.1', 'poll', 'root', 'root');

        $identifier = $this->_request->query($this->_actionIdentifier, null, StringTransformer::getSharedInstance());

        if (false === isset($this->_actions[$identifier])) {

            $this->_response->status(404)->errors(['action' => "Action '{$identifier}' not found"])->end();

            return;

        }

        /** @var IApplicationHandler $handler */
        $handler = $this->_actions[$identifier];

        if (false === $handler->before($this->_request, $this->_response)) {

            $this->_response->setError('_handle.before', "Before action of '{$identifier}' failed")->end();

            return;
        }

        $handler->handle($this->_request, $this->_response);

        if (false === $handler->after()) {

            $this->_response->setError('_handle.after', "After action of '{$identifier}' failed");
        }

        $this->_response->end();
    }


    /**
     *
     * Registers handler for given action in current group
     *
     * @param string $identifier
     * @param IApplicationHandler $handler
     * @return Application
     * @throws ApplicationException
     */
    private function _action(string $identifier, IApplicationHandler $handler): Application
    {

        $identifier = sprintf("%s%s", (empty($this->_group) ? '' : "{$this->_group}."), $identifier);

        if (isset($this->_actions[$identifier]) && $this->_actions[$identifier] instanceof IApplicationHandler) {

            throw new ApplicationException(ApplicationException::ERROR_DUPLICATE_ACTION_HANDLER, $identifier);
        }

        $this->_actions[$identifier] = $handler;

        return $this;
    }

}