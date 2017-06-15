<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/15/17
 * Time: 4:10 PM
 */
spl_autoload_register(function ($class) {

    $classFilePath = __DIR__ . '/../' . str_replace('\\', '/', $class) . '.php';

    require_once $classFilePath;
});