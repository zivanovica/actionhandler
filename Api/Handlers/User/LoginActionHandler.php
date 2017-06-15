<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/14/17
 * Time: 10:15 PM
 */

namespace Api\Handlers\User;


use Core\CoreUtils\Singleton;
use Core\Libs\Application\IApplicationActionHandler;
use Core\Libs\Application\IApplicationHandlerMethod;
use Core\Libs\Request;
use Core\Libs\Response\Response;

class LoginActionHandler implements IApplicationActionHandler
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
     * Executes before "handle" method, if FALSE is returned "handle" won't be called and request will be finished
     * @param Request $request
     * @param Response $response
     * @return bool
     */
    public function before(Request $request, Response $response): bool
    {

        return true;
    }

    public function after(): void
    {
        // TODO: Implement after() method.
    }

    public function handle(Request $request, Response $response): void
    {
        $response->data([
            'message' => 'Login handler good.'
        ]);
    }
}