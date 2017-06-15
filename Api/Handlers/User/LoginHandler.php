<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/14/17
 * Time: 10:15 PM
 */

namespace Api\Handlers\User;


use Core\CoreUtils\Singleton;
use Core\Libs\Application\IApplicationHandler;
use Core\Libs\Request;
use Core\Libs\Response;

class LoginHandler implements IApplicationHandler
{

    use Singleton;

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