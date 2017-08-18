<?php

namespace RequestHandler\Utils\SingletonFactory;

use RequestHandler\Exceptions\SingletonFactoryException;

/**
 *
 * Even though "Singleton" is trait, we use static $_instances as array that matches [CLASS NAME]=>ClassObject.
 * This is done to achieve using traits from children classes
 *
 * @package Modules\Singleton
 */
abstract class SingletonFactory implements ISingletonFactory
{

    private static $_map = [];

    /** @var array */
    private static $_instances = [];

    /**
     *
     * Returns singleton instance of object using "func_get_args" to retrieve arguments if new instance is required
     *
     * @param string $interface
     * @return object
     */
    public static function getSharedInstance(string $interface)
    {

        $args = func_get_args();

        unset($args[0]);

        return static::getSharedInstanceArgs($interface, $args);
    }

    /**
     *
     * Returns singleton instance of object using second argument as construct parameters
     *
     * @param string $interface
     * @param array $parameters
     * @return object
     */
    public static function getSharedInstanceArgs(string $interface, array $parameters = [])
    {

        $class = static::_getInterfaceClass($interface);

        if (empty(static::$_instances[$class])) {

            static::$_instances[$class] = static::getNewInstanceArgs($class, $parameters);
        }

        return static::$_instances[$class];
    }

    /**
     *
     * Maps interface with corresponding class
     *
     * @param string $interface
     * @param string $className
     * @return void
     * @throws SingletonFactoryException
     */
    public static function map(string $interface, string $className): void
    {

        if (false === (interface_exists($interface) || class_exists($interface))) {

            throw new SingletonFactoryException(SingletonFactoryException::ERROR_INVALID_INTERFACE, $interface);
        }

        if (false === class_exists($className)) {

            throw new SingletonFactoryException(SingletonFactoryException::ERROR_INVALID_CLASS, $className);
        }

        if (false === is_subclass_of($className, $interface) && 0 !== strcasecmp($className, $interface)) {

            throw new SingletonFactoryException(
                SingletonFactoryException::ERROR_INTERFACE_MISMATCH,
                "{$className} does not implements {$interface}"
            );
        }

        static::$_map[$interface] = $className;
    }

    /**
     * @param array $map
     * @return void
     */
    public static function setMap(array $map): void
    {

        foreach ($map as $interface => $class) {

            static::map($interface, $class);
        }
    }

    /**
     *
     * Returns new instance of class event if constructor is not accessible (private/protected)
     *
     * @param string $class
     * @param array $arguments
     * @return $this
     */
    private static function getNewInstanceArgs(string $class, array $arguments = [])
    {

        $reflection = new \ReflectionClass($class);

        $instance = $reflection->newInstanceWithoutConstructor();

        $constructor = $reflection->getConstructor();

        if ($constructor instanceof \ReflectionMethod) {

            $constructor->setAccessible(true);

            $constructor->invokeArgs($instance, $arguments);
        }

        return $instance;
    }

    /**
     *
     * Retrieve corresponding class mapped to given interface
     * If map is not found, original interface name is returned
     *
     * @param string $interface
     * @return string
     */
    private static function _getInterfaceClass(string $interface): string
    {

        $mapped = empty(static::$_map[$interface]) ? $interface : static::$_map[$interface];

        if (class_exists($mapped)) {

            return $mapped;
        } else if (interface_exists($mapped)) {

            return self::_getInterfaceClass($mapped);
        }

        return $interface;
    }
}