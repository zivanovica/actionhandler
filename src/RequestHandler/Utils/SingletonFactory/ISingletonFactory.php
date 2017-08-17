<?php

namespace RequestHandler\Utils\SingletonFactory;

use RequestHandler\Exceptions\SingletonFactoryException;

interface ISingletonFactory
{

    /**
     *
     * Returns singleton instance of object using "func_get_args" to retrieve arguments if new instance is required
     *
     * @param string $interface
     * @return object
     */
    public static function getSharedInstance(string $interface);

    /**
     *
     * Returns singleton instance of object using second argument as construct parameters
     *
     * @param string $interface
     * @param array $parameters
     * @return object
     */
    public static function getSharedInstanceArgs(string $interface, array $parameters = []);

    /**
     *
     * Maps interface with corresponding class
     *
     * @param string $interface
     * @param string $className
     * @return void
     * @throws SingletonFactoryException
     */
    public static function map(string $interface, string $className): void;

    /**
     * @param array $map
     * @return void
     */
    public static function setMap(array $map): void;
}