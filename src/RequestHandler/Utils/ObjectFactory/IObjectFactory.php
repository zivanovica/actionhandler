<?php

namespace RequestHandler\Utils\ObjectFactory;

use RequestHandler\Exceptions\ObjectFactoryException;

interface IObjectFactory
{

    /**
     *
     * Returns singleton instance of object using second argument as construct parameters
     *
     * @param string $interface
     * @param array ...$arguments
     * @return mixed
     * @throws ObjectFactoryException
     */
    public static function create(string $interface, ... $arguments);

    /**
     *
     * Maps interface with corresponding class
     *
     * @param string $interface
     * @param string $implementation
     * @return void
     * @throws ObjectFactoryException
     */
    public static function register(string $interface, string $implementation): void;
}