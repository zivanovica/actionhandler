<?php

namespace RequestHandler\Utils\DataFilter\Filters;

use RequestHandler\Modules\Model\IModel;
use RequestHandler\Utils\DataFilter\IDataFilter;
use RequestHandler\Utils\ObjectFactory\ObjectFactory;
use RequestHandler\Exceptions\ModelFilterException;
use RequestHandler\Modules\Model\Model;

/**
 *
 * Filter that is used to convert value to corresponding model
 *
 * @package Core\CoreUtils\DataFilter\Filters
 */
class ModelFilter implements IDataFilter
{


    /** @var array */
    private static $_cached = [];

    /** @var string */
    private $_modelName;

    /** @var string */
    private $_field;

    private function __construct(string $modelName, ?string $field = null)
    {

        $this->_modelName = $modelName;

        $this->_field = $field;
    }

    /**
     *
     * Use input value as "field" value to fetch from database and return that model
     * If value, field and model were already filtered, method will return model from $_cached property
     *
     * @param mixed $value
     * @return Model|null
     * @throws ModelFilterException
     */
    public function filter($value)
    {

        $uniqueId = hash('sha256', "{$this->_modelName}-{$this->_field}-{$value}");

        if (false === isset(self::$_cached[$uniqueId])) {

            /** @var Model $model */
            $model = new $this->_modelName();

            if (false === $model instanceof IModel) {

                throw new ModelFilterException(ModelFilterException::ERROR_INVALID_MODEL_CLASS, $this->_modelName);
            }

            self::$_cached[$uniqueId] = empty($this->_field) ? $model->find($value) : $model->findOneWhere([$this->_field => $value]);
        }

        return self::$_cached[$uniqueId];
    }
}