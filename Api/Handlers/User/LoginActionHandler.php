<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/14/17
 * Time: 10:15 PM
 */

namespace Api\Handlers\User;


use Api\Middlewares\AuthenticateMiddleware;
use Api\Middlewares\AuthorizeMiddleware;
use Core\CoreUtils\Singleton;
use Core\Libs\Application\IApplicationActionAfterHandler;
use Core\Libs\Application\IApplicationActionHandler;
use Core\Libs\Application\IApplicationActionMiddleware;
use Core\Libs\Middleware\Middleware;
use Core\Libs\Request;
use Core\Libs\Response\Response;

class LoginActionHandler implements IApplicationActionHandler, IApplicationActionAfterHandler, IApplicationActionMiddleware
{

    use Singleton;

    public function handle(Request $request, Response $response): void
    {
        $response->data([
            'message' => 'Login handler good.'
        ]);
    }

    /**
     *
     * Executes after "handle" method, doesn't affect on "handle" execution nor request
     *
     */
    public function after(): void
    {

    }

    public function middleware(Middleware $middleware): Middleware
    {
        return $middleware
            ->add(new AuthenticateMiddleware())
            ->add(new AuthorizeMiddleware())
        ;
    }
}