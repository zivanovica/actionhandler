<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/14/17
 * Time: 10:21 PM
 */

namespace Api\Handlers\Idea;


use Core\CoreUtils\Singleton;
use Core\Libs\Application\IApplicationActionHandler;
use Core\Libs\Application\IApplicationActionMiddleware;
use Core\Libs\Application\IApplicationActionValidator;
use Core\Libs\Application\IApplicationHandlerMethod;
use Core\Libs\Middleware\Middleware;
use Core\Libs\Request;
use Core\Libs\Response\Response;

class ListActionHandler implements IApplicationActionHandler, IApplicationActionMiddleware, IApplicationActionValidator
{

    use Singleton;

    public function methods(): array
    {
        return [
            IApplicationHandlerMethod::GET,
            IApplicationHandlerMethod::DELETE
        ];
    }

    /**
     *
     * Executes when related action is requested
     *
     * @param Request $request
     * @param Response $response
     */
    public function handle(Request $request, Response $response): void
    {
        // TODO: Implement handle() method.
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
        // TODO: Implement middleware() method.
    }

    /**
     *
     * Validates should current action be handled or not.
     *
     * NOTE: this is executed AFTER middlewares
     *
     * @param Request $request
     * @return bool
     */
    public function validate(Request $request): bool
    {
        // TODO: Implement validate() method.
    }
}