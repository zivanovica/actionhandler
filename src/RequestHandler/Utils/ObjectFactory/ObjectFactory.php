<?php

namespace RequestHandler\Utils\ObjectFactory;

use RequestHandler\Exceptions\ObjectFactoryException;

/**
 *
 * @package Modules\Singleton
 */
class ObjectFactory implements IObjectFactory
{

    /** @var array */
    private static $map = [];

    /** @var array */
    private static $instances = [];

    /**
     *
     * Returns singleton instance of object using second argument as construct parameters
     *
     * @param string $interface
     * @param array ...$arguments
     *
     * @return \object
     *
     * @throws \ReflectionException
     */
    public static function create(string $interface, ... $arguments): \object
    {
        $class = static::getInterfaceClass($interface);

        if (empty(static::$instances[$class])) {
            static::$instances[$class] = static::getNewInstanceArgs($class, $arguments);
        }

        return static::$instances[$class];
    }

    /**
     *
     * Creates new instance
     *
     * @param string $interface
     * @param array ...$arguments
     *
     * @return \object
     *
     * @throws \ReflectionException
     */
    public static function createNew(string $interface, ... $arguments): \object
    {
        return static::getNewInstanceArgs(static::getInterfaceClass($interface), $arguments);
    }

    /**
     *
     * Maps interface with corresponding class
     *
     * @param string $interface
     * @param string $implementation
     * @return void
     * @throws ObjectFactoryException
     */
    public static function register(string $interface, string $implementation): void
    {

        if (false === (interface_exists($interface) || class_exists($interface))) {
            throw new ObjectFactoryException(ObjectFactoryException::BAD_CLASS, $implementation);
        }

        if (false === is_subclass_of($implementation, $interface) && 0 !== strcasecmp($implementation, $interface)) {
            throw new ObjectFactoryException(
                ObjectFactoryException::INTERFACE_MISMATCH,
                "{$implementation} does not implements {$interface}"
            );
        }

        static::$map[$interface] = $implementation;
    }

    /**
     *
     * Map multiple interfaces to their class
     *
     * @param array $interfaceMap
     */
    public static function set(array $interfaceMap): void
    {
        foreach ($interfaceMap as $interface => $implementation) {
            self::register($interface, $implementation);
        }
    }

    /**
     *
     * Returns new instance of class event if constructor is not accessible (private/protected)
     *
     * @param string $class
     * @param array $arguments
     *
     * @return \object
     *
     * @throws \ReflectionException
     */
    private static function getNewInstanceArgs(string $class, array $arguments = []): \object
    {

        $reflection = new \ReflectionClass($class);

        $instance = $reflection->newInstanceWithoutConstructor();

        $constructor = $reflection->getConstructor();

        if ($constructor instanceof \ReflectionMethod) {

            $constructor->setAccessible(true);

            $constructor->invokeArgs($instance, self::getDependencies($constructor, $arguments));
        }

        return $instance;
    }

    /**
     *
     * Retrieve array of parameters required for given method.
     *
     * If parameter is typed then instance of that object will be assigned
     * If parameter is not typed and its optional, its default value will be used.
     * If parameter is not typed and optional but its nullable, then "NULL" value will be assigned.
     * If parameter is not typed, optional nor nullable but there is value in $parameters that can be assigned,
     * that value is used.
     * If parameter is not typed, optional nor nullable and $parameters is empty then exception will be thrown
     *
     * @param null|\ReflectionMethod $reflectionMethod
     * @param array $parameters
     *
     * @return array
     *
     * @throws ObjectFactoryException
     * @throws \ReflectionException
     */
    private static function getDependencies(\ReflectionMethod $reflectionMethod, array $parameters = []): array
    {

        $arguments = [];

        foreach ($reflectionMethod->getParameters() as $parameter) {

            if ($parameter->getClass()) {

                $arguments[] = ObjectFactory::create($parameter->getClass()->getName());
            } else if (empty($parameters) && $parameter->isOptional()) {

                $arguments[] = $parameter->getDefaultValue();
            } else if (empty($parameters) && $parameter->allowsNull()) {

                $arguments[] = null;
            } else if (empty($parameters)) {

                throw new ObjectFactoryException(
                    ObjectFactoryException::UNRESOLVED_PARAMETER,
                    "{$parameter->getName()} for {$reflectionMethod->getDeclaringClass()->getName()}"
                );
            } else if (false === empty($parameters)) {

                $arguments[] = array_shift($parameters);
            }
        }

        return $arguments;
    }

    /**
     *
     * Retrieve corresponding class mapped to given interface
     * If map is not found, original interface name is returned
     *
     * @param string $interface
     * @return string
     */
    private static function getInterfaceClass(string $interface): string
    {

        $mapped = empty(static::$map[$interface]) ? $interface : static::$map[$interface];

        if (interface_exists($mapped)) {

            return self::getInterfaceClass($mapped);
        } else if (class_exists($mapped)) {

            return $mapped;
        }

        return $interface;
    }
}