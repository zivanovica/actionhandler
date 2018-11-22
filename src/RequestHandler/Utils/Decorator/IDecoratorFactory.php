<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 8/17/17
 * Time: 3:01 PM
 */

namespace RequestHandler\Utils\Decorator;


use RequestHandler\Exceptions\DecoratorFactoryException;
use RequestHandler\Utils\Decorator\Types\IDecorator;

interface IDecoratorFactory
{
    /**
     *
     * Creates new decorator
     *
     * @param string $decoratorClassName
     * @param mixed $object
     * @param array $decoratorArgs
     * @return IDecorator
     * @throws DecoratorFactoryException
     */
    public static function create($decoratorClassName, $object, $decoratorArgs = []);
}