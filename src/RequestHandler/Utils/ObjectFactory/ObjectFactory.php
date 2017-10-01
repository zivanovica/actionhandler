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
    private static $_map = [];

    /** @var array */
    private static $_instances = [];

    /**
     *
     * Returns singleton instance of object using second argument as construct parameters
     *
     * @param string $interface
     * @param array ...$arguments
     * @return mixed
     */
    public static function create(string $interface, ... $arguments)
    {

        $class = static::_getInterfaceClass($interface);

        if (empty(static::$_instances[$class])) {

            static::$_instances[$class] = static::getNewInstanceArgs(
                $class, is_array($arguments) ? $arguments : []
            );
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
     * @throws ObjectFactoryException
     */
    public static function map(string $interface, string $className): void
    {

        if (false === (interface_exists($interface) || class_exists($interface))) {

            throw new ObjectFactoryException(ObjectFactoryException::ERROR_INVALID_INTERFACE, $interface);
        }

        if (false === class_exists($className)) {

            throw new ObjectFactoryException(ObjectFactoryException::ERROR_INVALID_CLASS, $className);
        }

        if (false === is_subclass_of($className, $interface) && 0 !== strcasecmp($className, $interface)) {

            throw new ObjectFactoryException(
                ObjectFactoryException::ERROR_INTERFACE_MISMATCH,
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
     * @return array
     * @throws ObjectFactoryException
     */
    private static function getDependencies(?\ReflectionMethod $reflectionMethod, array $parameters = []): array
    {

        if (null === $reflectionMethod) {

            return [];
        }

        $arguments = [];

        foreach ($reflectionMethod->getParameters() as $parameter) {

            if ($parameter->getClass()) {

                $arguments[] = ObjectFactory::create($parameter->getClass()->getName());
            } else if (null === $parameter->getClass() && $parameter->isOptional()) {

                $arguments[] = $parameter->getDefaultValue();
            } else if (empty($parameters) && $parameter->allowsNull()) {

                $arguments[] = null;
            } else if (empty($parameters)) {

                throw new ObjectFactoryException(
                    ObjectFactoryException::ERROR_UNRESOLVED_PARAMETER,
                    "{$parameter->getName()} for {$parameter->getDeclaringClass()->getName()}"
                );
            }

            $arguments[] = array_shift($parameters);
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