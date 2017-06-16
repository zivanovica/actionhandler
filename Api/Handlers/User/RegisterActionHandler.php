<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/14/17
 * Time: 10:20 PM
 */

namespace Api\Handlers\User;


use Core\CoreUtils\Singleton;
use Core\Libs\Application\IApplicationActionHandler;
use Core\Libs\Application\IApplicationHandlerMethod;
use Core\Libs\Request;
use Core\Libs\Response\Response;

class RegisterActionHandler implements IApplicationActionHandler
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
        $response->data([
            'message' => 'Register handler good.'
        ]);
    }
}