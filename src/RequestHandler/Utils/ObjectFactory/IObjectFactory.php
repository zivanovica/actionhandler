<?php

namespace RequestHandler\Utils\ObjectFactory;

use RequestHandler\Exceptions\FactoryException;

interface IObjectFactory
{

    /**
     *
     * Returns singleton instance of object using second argument as construct parameters
     *
     * @param string $interface
     * @param array ...$arguments
     * @return mixed
     */
    public static function create(string $interface, ... $arguments);

    /**
     *
     * Maps interface with corresponding class
     *
     * @param string $interface
     * @param string $className
     * @return void
     * @throws FactoryException
     */
    public static function map(string $interface, string $className): void;

    /**
     * @param array $map
     * @return void
     */
    public static function setMap(array $map): void;
}