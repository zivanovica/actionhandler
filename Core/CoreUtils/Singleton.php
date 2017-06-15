<?php

/**
 * User: Aleksandar Zivanovic <coapsyfactor@gmail.com>
 * Date: 22.6.2016.
 * Time: 02.53
 */

namespace Core\CoreUtils;

trait Singleton
{
    /** @var object */
    private static $instance;

    /**
     * @return object|$this
     */
    public static function getSharedInstance()
    {
        if (is_null(self::$instance)) {
            self::$instance = self::getInstance(func_get_args());
        }

        return self::$instance;
    }

    /**
     * @param array $arguments
     * @return $this|object
     */
    public static function getSharedInstanceArgs(array $arguments)
    {
        return self::getSharedInstance($arguments);
    }

    /**
     * @return mixed|$this
     */
    public static function getNewInstance()
    {
        return self::getInstance(func_get_args());
    }

    /**
     * @param array $arguments
     * @return $this
     */
    public static function getNewInstanceArgs(array $arguments)
    {
        return self::getInstance($arguments);
    }

    /**
     * @return $this
     */
    private static function getInstance(array $arguments)
    {
        $reflection = new \ReflectionClass(static::class);

        $instance = $reflection->newInstanceWithoutConstructor();

        $constructor = $reflection->getConstructor();

        if ($constructor) {

            $constructor->setAccessible(true);

            $constructor->invokeArgs($instance, $arguments);
        }

        return $instance;
    }
}