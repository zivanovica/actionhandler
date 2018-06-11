<?php

namespace RequestHandler\Utils\DataFilter\Filters;

use RequestHandler\Exceptions\ModelFilterException;
use RequestHandler\Modules\Entity\IModel;
use RequestHandler\Modules\Entity\IRepository;
use RequestHandler\Modules\Entity\Model;
use RequestHandler\Utils\DataFilter\IDataFilter;
use RequestHandler\Utils\ObjectFactory\ObjectFactory;

/**
 *
 * Filter that is used to convert value to corresponding model
 *
 * @package Core\CoreUtils\DataFilter\Filters
 */
class EntityModelFilter implements IDataFilter
{

    const DEFAULT_FIELD = 'id';

    /** @var array */
    private static $_cached = [];

    /** @var string */
    private $_repositoryClassName;

    /** @var string */
    private $_searchField;

    public function __construct(string $repository, string $searchField = EntityModelFilter::DEFAULT_FIELD)
    {

        $this->_repositoryClassName = $repository;

        $this->_searchField = $searchField;
    }

    /**
     *
     * Use input value as "field" value to fetch from database and return that model
     * If value, field and model were already filtered, method will return model from $_cached property
     *
     * @param mixed $value
     *
     * @return Model|null
     *
     * @throws ModelFilterException
     * @throws \ReflectionException
     */
    public function filter($value)
    {

        $uniqueId = hash('sha256', "{$this->_repositoryClassName}-{$this->_searchField}-{$value}");

        if (false === isset(self::$_cached[$uniqueId])) {

            /** @var IRepository $repository */
            $repository = ObjectFactory::create($this->_repositoryClassName);

            self::$_cached[$uniqueId] = $repository->find("{$this->_searchField} = ?", [$value]);

            if (null !== self::$_cached[$uniqueId] && false === self::$_cached[$uniqueId] instanceof IModel) {

                throw new ModelFilterException(ModelFilterException::ERR_BAD_MODEL_CLASS, $this->_repositoryClassName);
            }
        }

        return self::$_cached[$uniqueId];
    }
}