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
        $user = $this->_createUser($request->data('email'), $request->data('password'));

        if (false === $user instanceof User) {

            $response
                ->status(500)
                ->setError('user.create', 'Failed to create user.');

            return;
        }

        $account = $this->_createAccount($user, $request->data('first_name'), $request->data('last_name'));

        if (false === $account instanceof Account) {

            $user->delete();

            $response
                ->status(500)
                ->setError('user.account_create', 'Failed to create user account.');

            return;
        }

        $response->setData('message', 'User successfully created');
    }

    /**
     *
     * Creates user profile with given email, hashed password, activation code and status
     *
     * @param string $email Email user will use to login
     * @param string $password Unhashed password
     * @return User|null
     */
    private function _createUser(string $email, string $password): ?User
    {
        $user = User::getNewInstance([
            'email' => $email,
            'password' => password_hash($password, PASSWORD_BCRYPT),
            'code' => hash('sha256', uniqid('', true)),
            'status' => User::STATUS_INACTIVE
        ]);

        if ($user->save()) {

            return $user;
        }

        return null;
    }

    /**
     *
     * Creates account for given user
     *
     * @param User $user User to whom account belongs to
     * @param string $firstName User first name
     * @param string $lastName User last name
     * @return Account|null
     */
    private function _createAccount(User $user, string $firstName, string $lastName): ?Account
    {

        $account = Account::getNewInstance([
            'user_id' => $user,
            'first_name' => $firstName,
            'last_name' => $lastName
        ]);

        if ($account->save()) {

            return $account;
        }

        return null;
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

        if (empty($validator->getErrors())) {

            return IResponseStatus::OK;
        }

        Response::getSharedInstance()->errors($validator->getErrors());

        return IResponseStatus::BAD_REQUEST;

    }
}