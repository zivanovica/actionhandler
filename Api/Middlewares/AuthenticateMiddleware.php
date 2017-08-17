<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/19/17
 * Time: 1:09 PM
 */

namespace Api\Middlewares;


use Api\Models\Token;
use Api\Models\User;
use RequestHandler\Utils\DataFilter\Filters\ModelFilter;
use RequestHandler\Utils\Singleton;
use RequestHandler\Modules\Middleware\IMiddleware;
use RequestHandler\Modules\Middleware\Middleware;
use RequestHandler\Modules\Request\Request;
use RequestHandler\Modules\Response\IResponseStatus;
use RequestHandler\Modules\Response\Response;

class AuthenticateMiddleware implements IMiddleware
{

    use Singleton;

    public function run(Request $request, Response $response, Middleware $middleware): void
    {

        /** @var Token $token */
        $token = $request->get('token', null, ModelFilter::getNewInstance(Token::class, 'value'));

        if (false === $token instanceof Token) {

            $this->_fail($response, null, 'token', 'Invalid token');
        } else if ($token->hasExpired()) {

            $this->_fail($response, $token, 'token.lifespan', 'Token has expired');
        } else if (false === $token->user() instanceof User) {

            $this->_fail($response, $token, 'token.owner', 'Invalid token owner');
        }

        if (false === $response->hasErrors()) {

            $token->refresh();

            $request->setToken($token);

            $middleware->next();
        }
    }

    /**
     *
     * Will set response status to 401 with given error message, and if token is provided it will be removed
     *
     * @param Response $response
     * @param Token|null $token
     * @param string $error
     * @param string $message
     */
    private function _fail(Response $response, ?Token $token, string $error, string $message): void
    {

        $response->status(IResponseStatus::UNAUTHORIZED)->addError($error, $message);

        if ($token instanceof Token) {

            $token->delete();
        }
    }
}