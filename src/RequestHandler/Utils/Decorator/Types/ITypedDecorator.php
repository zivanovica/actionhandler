<?php

namespace RequestHandler\Utils\Decorator\Types;

interface ITypedDecorator extends IDecorator
{

    /**
     *
     * Retrieve model class name
     *
     * @return string
     */
    public function getObjectClass(): string;
}