<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/14/17
 * Time: 9:44 PM
 */

namespace Core\Libs\Application;

use Core\Libs\Request;
use Core\Libs\Response\Response;

interface IApplicationActionHandler
{

    /**
     * @return array All acceptable request methods for current handler
     */
    public function methods(): array;

    /**
     *
     * Executes when related action is requested
     *
     * @param Request $request
     * @param Response $response
     */
    public function handle(Request $request, Response $response): void;
}