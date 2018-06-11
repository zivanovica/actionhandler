<?php

namespace RequestHandler\Utils\Decorator;

use RequestHandler\Exceptions\DecoratorFactoryException;
use RequestHandler\Utils\Decorator\Types\IDecorator;
use RequestHandler\Utils\Decorator\Types\ITypedDecorator;

abstract class DecoratorFactory implements IDecoratorFactory
{

    /**
     *
     * Creates new decorator
     *
     * @param string $decoratorClassName
     * @param mixed $object
     * @param array $decoratorArgs
     *
     * @return IDecorator
     *
     * @throws DecoratorFactoryException
     * @throws \ReflectionException
     */
    public static function create($decoratorClassName, $object, $decoratorArgs = [])
    {

        if (false === class_exists($decoratorClassName)) {

            throw new DecoratorFactoryException(DecoratorFactoryException::ERR_DECORATOR_NOT_FOUND, $decoratorClassName);
        }

        /** @var IDecorator $decorator */
        $decorator = (new \ReflectionClass($decoratorClassName))->newInstanceArgs($decoratorArgs);

        if (false === $decorator instanceof IDecorator) {

            throw new DecoratorFactoryException(DecoratorFactoryException::ERR_DECORATOR_NOT_VALID, $decoratorClassName);
        }

        if ($decorator instanceof ITypedDecorator) {

            self::_validateDecoratorObjectType($decorator, $object);
        }

        $decorator->decorate($object);

        return $decorator;
    }

    /**
     *
     * Validates type of object that will be passed to decorator if everything is good
     *
     * @param ITypedDecorator $decorator
     * @param mixed $object
     *
     * @throws DecoratorFactoryException
     */
    private static function _validateDecoratorObjectType(ITypedDecorator $decorator, $object): void
    {

        if (false === is_object($object)) {

            throw new DecoratorFactoryException(DecoratorFactoryException::ERR_BAD_OBJECT_TYPE, gettype($object));
        }

        if (false === is_a($object, $decorator->getObjectClass())) {

            $class = get_class($object);

            throw new DecoratorFactoryException(
                DecoratorFactoryException::ERR_OBJECT_TYPE_MISMATCH, "Expected {$decorator->getObjectClass()} got {$class}"
            );
        }
    }
}