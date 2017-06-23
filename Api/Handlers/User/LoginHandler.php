<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/22/17
 * Time: 6:19 PM
 */

namespace Api\Handlers\User;


use Api\Middlewares\AuthenticateMiddleware;
use Api\Models\Token;
use Api\Models\User;
use Core\CoreUtils\InputValidator\InputValidator;
use Core\Libs\Application\IApplicationActionHandler;
use Core\Libs\Application\IApplicationActionMiddleware;
use Core\Libs\Application\IApplicationActionValidator;
use Core\Libs\Middleware\Middleware;
use Core\Libs\Request;
use Core\Libs\Response\IResponseStatus;
use Core\Libs\Response\Response;

class LoginHandler implements IApplicationActionHandler, IApplicationActionValidator, IApplicationActionMiddleware
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
     * Used to register all middlewares that should be executed before handling acton
     *
     * @param Middleware $middleware
     * @return Middleware
     */
    public function middleware(Middleware $middleware): Middleware
    {

        return $middleware->add(new AuthenticateMiddleware());
    }

    /**
     *
     * Validates should current action be handled or not.
     * Status code returned from validate will be used as response status code.
     * If this method does not return status 200 or IResponseStatus::OK script will end response and won't handle rest of request.
     *
     * NOTE: this is executed AFTER middlewares
     *
     * @param Request $request
     * @param Response $response
     * @return bool
     */
    public function validate(Request $request, Response $response): bool
    {

        $validator = InputValidator::getSharedInstance($request->allData());

        if (false === $validator->validate(['email' => 'required', 'password' => 'required'])) {

            $response->status(IResponseStatus::BAD_REQUEST)->errors($validator->getErrors());

            return false;
        }

        return true;
    }
}