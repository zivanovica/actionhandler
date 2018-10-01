<?php

namespace RequestHandler\Modules\Entity;

use RequestHandler\Exceptions\ModelException;
use RequestHandler\Utils\{
    DataFilter\IDataFilter, Decorator\IDecorator
};

/**
 *
 * Extend this class to ensure that child class is model that can be stored, fetched and removed from database
 *
 * @package Core\Libs\Model
 */
abstract class Model implements IModel
{

    /** @var array */
    protected static $_loadedFields;

    /** @var array */
    protected $_attributes = [];

    /** @var array Properties that will be excluded in toArray() method */
    protected $_hidden = [];

    /** @var IDataFilter[] */
    protected $_fields;

    /** @var bool Flag that is used by Repository to determine should entity be updated on flush or not */
    protected $_isDirty;

    /**
     * @param array $attributes Initial model values
     */
    public function __construct(array $attributes = [])
    {

        $this->_attributes = $attributes;

        $this->_fields = $this->fields();

        $this->hydrate($attributes);
    }

    /**
     * Retrieves value of primary key for current entity
     *
     * @return mixed
     */
    public function primaryValue()
    {

        return $this->field($this->primary());
    }

    /**
     *
     * Retrieve value of model field (column)
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function field(string $name, $default = null)
    {

        if (false === isset($this->_fields[$name])) {

            throw new ModelException(ModelException::BAD_FIELD, $name);
        }

        $rawFieldValue = isset($this->_attributes[$name]) ? $this->_attributes[$name] : $default;

        return $this->_fields[$name] instanceof IDataFilter ?
            $this->_fields[$name]->filter($rawFieldValue) : $rawFieldValue;
    }

    /**
     *
     * Set raw value for given field (column)
     *
     * NOTE: If filter to field is applied, then raw value is filtered when reading
     *
     * @param string $name
     * @param $value
     * @return IModel
     */
    public function setField(string $name, $value): IModel
    {

        if (false === isset($this->_fields[$name])) {

            throw new ModelException(ModelException::BAD_FIELD, $name);
        }

        $oldValue = $this->_attributes[$name];

        $this->_attributes[$name] = $value instanceof IModel ? $value->primaryValue() : $value;

        if ($oldValue !== $this->_attributes[$name]) {

            $this->_isDirty = true;
        }

        return $this;
    }

    /**
     *
     * If method returns true model will be updated when repository flushes
     *
     * @return bool
     */
    public function isDirty(): bool
    {

        return $this->_isDirty;
    }

    /**
     *
     * Hydrate model with new values
     *
     * @param array $data
     */
    public function hydrate(array $data): void
    {

        foreach ($data as $field => $value) {

            $this->setField($field, $value);
        }

        $this->_isDirty = false;
    }

    /**
     *
     * Retrieve current model primary key identifier
     *
     * @return string Name of primary key field
     */
    public function primary(): string
    {

        return IModel::DEFAULT_PRIMARY_KEY;
    }

    /**
     *
     * Validates does model (entity) exists in storage
     *
     * @return bool
     */
    public function exists(): bool
    {

        return $this->primary() && $this->primaryValue();
    }

    /**
     *
     * Retrieve all parameters with its associated values, parameters stored in "_hidden" property won't be retrieved in array
     *
     * @return array
     */
    public function toArray(): array
    {

//        $values = array_diff_key($this->_attributes, array_flip($this->_hidden));

        foreach ($this->_fields as $field => $value) {

            unset($value);

            $values[$field] = $this->field($field);
        }

        return $values;
    }
}