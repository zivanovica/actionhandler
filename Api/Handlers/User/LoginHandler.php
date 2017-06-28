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
use Core\Libs\Application\IApplicationRequestHandler;
use Core\Libs\Application\IApplicationRequestValidator;
use Core\Libs\Request\Request;
use Core\Libs\Response\IResponseStatus;
use Core\Libs\Response\Response;

class LoginHandler implements IApplicationRequestHandler, IApplicationRequestValidator
{

    /**
     *
     * Executes when related action is requested
     *
     * @param Request $request
     * @param Response $response
     * @return Response
     */
    public function handle(Request $request, Response $response): Response
    {

        $user = User::getSharedInstance()->getUserByEmailAndPassword(
            $request->data('email'),
            $request->data('password')
        );

        if (false === $user instanceof User) {

            return $response
                ->status(IResponseStatus::UNAUTHORIZED)->addError('credentials', 'Invalid login credentials.');
        }

        $token = Token::getSharedInstance()->create($user);

        if (false === $token instanceof Token) {

            return $response
                ->status(IResponseStatus::INTERNAL_ERROR)->addError('internal', 'There were some issues.');
        }

        return $response->addData('token', $token->getAttribute('value'));
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

        return $validator->validate(['email' => 'required', 'password' => 'required']);
    }
}