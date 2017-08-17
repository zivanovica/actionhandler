<?php

namespace Api\Models\Decorators;

use Api\Models\Idea;
use Api\Models\User;
use RequestHandler\Utils\DataFilter\Filters\ModelFilter;
use RequestHandler\Utils\Decorator\ITypedDecorator;

class UserPublicInfoDecorator implements ITypedDecorator
{

    /** @var User */
    private $_user;

    /**
     *
     * Sets object that will be decorated
     *
     * @param $object
     * @return void
     */
    public function decorate($object): void
    {

        $this->_user = $object;
    }

    /**
     *
     * Retrieve only public information for user
     *
     * @return array
     */
    public function getInfo(): array
    {

        $this->_user->getAttribute('id', ModelFilter::getNewInstance(Idea::class));

        return $this->_user->getAttributes(['id', 'email']);
    }

    /**
     *
     * Retrieve model class name
     *
     * @return string
     */
    public function getObjectClass(): string
    {

        return User::class;
    }
}