<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/16/17
 * Time: 2:25 PM
 */

namespace Core\Libs\Application;


interface IApplicationActionAfterHandler
{

    /**
     *
     * Executes after "handle" method, doesn't affect on "handle" execution nor request
     *
     */
    public function after(): void;
}