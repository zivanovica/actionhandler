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
use Core\Libs\Application\IApplicationHandlerMethod;
use Core\Libs\Request;
use Core\Libs\Response\Response;

class UpdateActionHandler implements IApplicationActionHandler
{

    use Singleton;

    /**
     *
     * Executes when related action is requested
     *
     * @param Request $request
     * @param Response $response
     */
    public function handle(Request $request, Response $response): void
    {
        $response->data(['action' => 'Idea Update']);
    }
}