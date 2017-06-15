<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/14/17
 * Time: 10:21 PM
 */

namespace Api\Handlers\Idea;


use Core\CoreUtils\Singleton;
use Core\Libs\Application\IApplicationHandler;
use Core\Libs\Request;
use Core\Libs\Response;

class UpdateHandler implements IApplicationHandler
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

    /**
     *
     * Executes after "handle" method, doesn't affect on "handle" execution nor request
     *
     */
    public function after(): void
    {
        // TODO: Implement after() method.
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
}