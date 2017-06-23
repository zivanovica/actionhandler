<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/22/17
 * Time: 12:56 PM
 */

namespace Api\Handlers\User;


use Api\Models\Account;
use Api\Models\User;
use Core\CoreUtils\InputValidator\InputValidator;
use Core\Libs\Application\IApplicationActionHandler;
use Core\Libs\Application\IApplicationActionValidator;
use Core\Libs\Request;
use Core\Libs\Response\IResponseStatus;
use Core\Libs\Response\Response;

class RegisterHandler implements IApplicationActionHandler, IApplicationActionValidator
{

    /**
     *
     * Executes when related action is requested
     *
     * @param Request $request
     * @param Response $response
     */
    public function handle(Request $request, Response $response): void
    {
        $user = User::getNewInstance()->createProfile(
            $request->data('email'), $request->data('password'),
            $request->data('first_name'), $request->data('last_name')
        );

        if (false === $user instanceof User) {

            $response
                ->status(500)
                ->addError('user.create', 'Failed to create user.');

            return;
        }

        $response->setData('message', 'User successfully created');
    }

    /**
     *
     * Validates should current action be handled or not.
     * Status code returned from validate will be used as response status code.
     * If this method does not return status 200 or IResponseStatus::OK script will end response and won't handle rest of request.
     *
     * NOTE: this is executed AFTER middlewares
     *
     * @return int If everything is good this MUST return 200 or IResponseStatus::OK
     */
    public function validate(): int
    {

        $validator = InputValidator::getSharedInstance();

        $validator->validate([
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6',
            'first_name' => 'required|min:2|max:64',
            'last_name' => 'required|min:2|max:64'
        ], Request::getSharedInstance()->allData());

        if (false === $validator->hasErrors()) {

            return IResponseStatus::OK;
        }

        Response::getSharedInstance()->errors($validator->getErrors());

        return IResponseStatus::BAD_REQUEST;

    }
}