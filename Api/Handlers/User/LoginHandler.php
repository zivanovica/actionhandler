<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/22/17
 * Time: 6:19 PM
 */

namespace Api\Handlers\User;


use Api\Models\Token;
use Api\Models\User;
use Core\CoreUtils\InputValidator\InputValidator;
use Core\Libs\Application\IApplicationActionHandler;
use Core\Libs\Application\IApplicationActionValidator;
use Core\Libs\Request;
use Core\Libs\Response\IResponseStatus;
use Core\Libs\Response\Response;

class LoginHandler implements IApplicationActionHandler, IApplicationActionValidator
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

        $user = User::getSharedInstance()->getUserByEmailAndPassword(
            $request->data('email'),
            $request->data('password')
        );

        if (false === $user instanceof User) {

            $response->status(IResponseStatus::UNAUTHORIZED)->addError('credentials', 'Invalid login credentials.');

            return;
        }

        $token = Token::getSharedInstance()->create($user);

        if (false === $token instanceof Token) {

            $response->status(IResponseStatus::INTERNAL_ERROR)->addError('internal', 'There were some issues.');

            return;
        }

        $response->addData('token', $token->getAttribute('value'));
    }

    /**
     *
     * Validates should current action be handled or not.
     * Status code returned from validate will be used as response status code.
     * If this method does not return status 200 or IResponseStatus::OK script will end response and won't handle rest of request.
     *
     * NOTE: this is executed AFTER middlewares
     *
     * @param InputValidator $validator
     * @return InputValidator
     */
    public function validate(InputValidator $validator): InputValidator
    {

        $validator->validate(['email' => 'required', 'password' => 'required']);

        return $validator;
    }
}