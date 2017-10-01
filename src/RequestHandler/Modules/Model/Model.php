<?php

namespace RequestHandler\Modules\Model;

use RequestHandler\Exceptions\ModelException;
use RequestHandler\Modules\Database\Database;
use RequestHandler\Modules\Database\IDatabase;
use RequestHandler\Utils\{
    DataFilter\IDataFilter, Decorator\DecoratorFactory, Decorator\IDecorator, Decorator\Types\ITypedDecorator, Factory\Factory
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
    protected $_attributes = [];

    /** @var array */
    protected $_updatedAttributes = [];

    /** @var array Properties that will be excluded in toArray() method */
    protected $_hidden = [];

    /** @var bool */
    protected $_isDirty;

    /** @var Database */
    protected $_db;

    /** @var ITypedDecorator[] */
    protected $_instantiatedDecorators = [];

    /**
     * @param array $attributes Initial model values
     * @param bool $isDirty
     */
    public function __construct(array $attributes = [], ?bool $isDirty = null)
    {

        $this->_db = Factory::create(IDatabase::class);

        if (false === $isDirty) {

            $this->_attributes = $attributes;

            $this->_isDirty = false;

            return;
        }

        $this->setAttributes($attributes);
    }

    /**
     *
     * Try setting parameter to model attributes
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {

        $this->setAttribute($name, $value);
    }

    /**
     *
     * Try fetching required parameter from model attributes
     *
     * @param string $name
     * @return mixed|null
     */
    public function __get($name)
    {

        return $this->getAttribute($name);
    }

    /**
     * Retrieves value of primary key for current entity
     *
     * @param IDataFilter $transformer
     * @return mixed
     */
    public function primaryValue(?IDataFilter $transformer = null)
    {

        return $this->getAttribute($this->primary(), $transformer);
    }

    /**
     *
     * Will retrieve single entity from database
     *
     * @param $primaryValue
     * @return $this|IModel|null
     */
    public function find($primaryValue): IModel
    {

        $results = $this->_db->fetch("SELECT * FROM `{$this->table()}` WHERE `{$this->primary()}` = ?;", [$primaryValue]);

        if (false === is_array($results)) {

            return new $this;
        }

        return new $this($results, false);
    }

    /**
     *
     * Retrieve single entity with given criteria
     *
     * @param array $criteria
     * @return $this|IModel
     */
    public function findOneWhere(array $criteria): IModel
    {
        $results = $this->_db->fetch($this->_buildCriteriaSelectQuery($this->table(), $criteria), array_values($criteria));

        if (false === is_array($results)) {

            return new $this;
        }

        return new $this($results, false);
    }

    /**
     *
     * Retrieve multiple entities with given criteria
     *
     * @param array $criteria
     * @return $this[]|Model[]|null
     */
    public function findWhere(array $criteria): array
    {

        return $this->_getModelsArray(
            $this->_db->fetchAll($this->_buildCriteriaSelectQuery($this->table(), $criteria), array_values($criteria))
        );
    }

    public function all(): ?array
    {

        return $this->_getModelsArray($this->_db->fetchAll("SELECT * FROM `{$this->table()}`;"));
    }

    /**
     *
     * Save current entity into database
     *
     * @return bool
     */
    public function save(): bool
    {

        if (false === $this->_isDirty) {

            return false;
        }

        $query = $this->_buildSaveQuery($this->table(), $this->primary(), $this->_updatedAttributes);

        $updateBindings = $this->_attributes;

        $id = $this->_db->store($query, array_merge(array_values($updateBindings), array_values($this->_updatedAttributes)));

        if ($id) {

            if ($this->primary()) {

                $this->setAttribute($this->primary(), $id);
            }

            $this->_attributes = array_merge($this->_attributes, $this->_updatedAttributes);

            $this->reset();

            return true;
        }

        return false;
    }

    /**
     *
     * Removes current entity from database
     *
     * @return bool
     * @throws ModelException
     */
    public function delete(): bool
    {

        if (empty($this->primary())) {

            throw new ModelException(ModelException::ERROR_MISSING_PRIMARY, 'Primary key is required for deleting.');
        }

        $id = $this->primaryValue();

        if (null === $id) {

            throw new ModelException(ModelException::ERROR_MISSING_PRIMARY, 'Primary key value is required for deleting.');
        }

        $deleted = (bool)$this->_db->delete("DELETE FROM `{$this->table()}` WHERE `{$this->primary()}` = ?;", [$id]);

        return $this->_isDirty = $deleted;
    }

    /**
     *
     * Bulk set of model values
     *
     * @param array $data
     * @return $this|IModel
     */
    public function setAttributes(array $data): IModel
    {

        foreach ($data as $name => $value) {

            $this->setAttribute($name, $value);
        }

        return $this;
    }

    /**
     *
     * Set specific field value
     *
     * @param string $name Field name
     * @param mixed $value Value of field
     * @param IDataFilter|null $transformer
     * @return $this|IModel
     * @throws ModelException
     */
    public function setAttribute(string $name, $value, ?IDataFilter $transformer = null): IModel
    {

        if (false === in_array($name, $this->fields())) {

            throw new ModelException(ModelException::ERROR_INVALID_FIELD, $name);
        }

        if ($value instanceof Model) {

            $value = $value->primaryValue();
        }

        $this->_isDirty = true;

        $this->_updatedAttributes[$name] = null === $transformer ? $value : $transformer->filter($value);

        return $this;
    }

    /**
     *
     * Get specific field value
     *
     * @param $name
     * @param IDataFilter|null $filter
     * @return mixed|null
     */
    public function getAttribute(string $name, ?IDataFilter $filter = null)
    {

        $attributes = array_merge($this->_attributes, $this->_updatedAttributes);

        $value = isset($attributes[$name]) ? $attributes[$name] : null;

        return null === $filter ? $value : $filter->filter($value);
    }

    /**
     *
     * Get bulk of field values
     *
     * TODO: Investigate could this anyhow result in false existence
     *
     * @param array $attributes
     * @return array
     */
    public function getAttributes(array $attributes): array
    {

        return array_intersect_key(array_merge($this->_attributes, $this->_updatedAttributes), array_flip($attributes));
    }

    /**
     *
     * Retrieve all parameters with its associated values, parameters stored in "_hidden" property won't be retrieved in array
     *
     * @return array
     */
    public function toArray(): array
    {

        return array_diff_key(array_merge($this->_attributes, $this->_updatedAttributes), array_flip($this->_hidden));
    }

    /**
     * Reset all changes to original values
     */
    public function reset(): void
    {

        $this->_updatedAttributes = [];

        $this->_isDirty = false;
    }

    public function dirty(): bool
    {

        return $this->_isDirty;
    }

    /**
     *
     * Generate INSERT query used for saving
     *
     * @param string $table Table name
     * @param string $primary Name of primary key
     * @param array $attributes Field values
     * @return string
     */
    private function _buildSaveQuery(string $table, string $primary, array $attributes): string
    {

        $fields = array_keys(array_merge($this->_attributes, $attributes));

        if (isset($attributes[$primary])) {

            unset($attributes[$primary]);
        }



        $updateFields = array_keys($attributes);

        $fieldMapper = function ($key): string {

            return "`{$key}`=?";
        };

        $fieldsString = implode(',', array_map($fieldMapper, $fields));

        $updateFieldsString = implode(',', array_map($fieldMapper, $updateFields));

        $query = "INSERT INTO `{$table}` SET {$fieldsString} ON DUPLICATE KEY UPDATE {$updateFieldsString};";

        return $query;
    }

    /**
     *
     * Build select query with "WHERE" criteria
     *
     * @param string $table
     * @param array $criteria
     * @return string
     */
    private function _buildCriteriaSelectQuery(string $table, array $criteria): string
    {

        $mapFunction = function (string $field): string {
            return "`{$field}` = ?";
        };

        $fields = implode(' AND ', array_map($mapFunction, array_keys($criteria)));

        return "SELECT * FROM `{$table}` WHERE {$fields};";
    }

    /**
     *
     * Will convert RAW array data to collection of models
     *
     * @param array|null $results
     * @return array|null
     */
    private function _getModelsArray(?array $results = null): ?array
    {

        if (null === $results) {

            return [];
        }


        $resultModels = [];

        foreach ($results as $result) {

            $resultModels[] = new $this($result, false);
        }

        return $resultModels;
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
     * @param string $decoratorClassName Class name of wanted decorator
     * @param array $decoratorParameters Parameters that will be passed to decorator constructor
     * @return ITypedDecorator
     */
    public function decorate($decoratorClassName, array $decoratorParameters = []): ITypedDecorator
    {

        if (empty($this->_instantiatedDecorators[$decoratorClassName])) {

            $this->_instantiatedDecorators[$decoratorClassName] = DecoratorFactory::create($decoratorClassName, $this, $decoratorParameters);
        }

        return $this->_instantiatedDecorators[$decoratorClassName];
    }
}