<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/14/17
 * Time: 9:44 PM
 */

namespace Core\Libs\Application;

use Core\Libs\Request;
use Core\Libs\Response;

interface IApplicationHandler
{

    /**
     *
     * Executes before "handle" method, if FALSE is returned "handle" won't be called and request will be finished
     *
     * @param Request $request
     * @param Response $response
     * @return bool
     */
    public function before(Request $request, Response $response): bool;

    /**
     *
     * Executes after "handle" method, doesn't affect on "handle" execution nor request
     *
     */
    public function after(): void;

    /**
     *
     * Executes when related action is requested
     *
     * @param Request $request
     * @param Response $response
     */
    public function handle(Request $request, Response $response): void;


}