<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/19/17
 * Time: 1:14 PM
 */

namespace Core\Libs\Application;


use Core\Libs\Request;

interface IApplicationActionValidator
{

    /**
     *
     * Validates should current action be handled or not.
     *
     * NOTE: this is executed AFTER middlewares
     *
     * @param Request $request
     * @return bool
     */
    public function validate(Request $request): bool;
}