<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/21/17
 * Time: 12:44 PM
 */

namespace Core\Libs\Model;


use Core\CoreUtils\DataTransformer\IDataTransformer;
use Core\CoreUtils\Singleton;
use Core\Exceptions\ModelException;
use Core\Libs\Database;

abstract class Model
{

    use Singleton;

    /** @var array */
    protected $_attributes;

    /** @var bool */
    protected $_isDirty;

    /** @var Database */
    protected $_db;

    /**
     * @param array $attributes Initial model values
     */
    public function __construct(array $attributes = [])
    {

        $this->_db = Database::getSharedInstance();

        $this->_isDirty = false === empty($attributes);

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
     * @param IDataTransformer $transformer
     * @return mixed
     */
    public function primaryValue(?IDataTransformer $transformer = null)
    {

        return $this->getAttribute($this->primary(), $transformer);
    }

    /**
     *
     * Will retrieve single entity from database
     *
     * @param $primaryValue
     * @return Model|null
     */
    public function find($primaryValue): ?Model
    {

        $results = $this->_db->fetch("SELECT * FROM `{$this->table()}` WHERE `{$this->primary()}` = ?;", [$primaryValue]);

        if (false === is_array($results)) {

            return null;
        }

        return new $this($results);
    }

    /**
     *
     * Retrieve single entity with given criteria
     *
     * @param array $criteria
     * @return Model|null
     */
    public function findOneWhere(array $criteria): ?Model
    {
        $results = $this->_db->fetch($this->_buildCriteriaSelectQuery($this->table(), $criteria), array_values($criteria));

        if (false === is_array($results)) {

            return null;
        }

        return new $this($results);
    }

    /**
     *
     * Retrieve multiple entities with given criteria
     *
     * @param array $criteria
     * @return Model[]|null
     */
    public function findWhere(array $criteria): ?array
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
     * @return int
     */
    public function save(): int
    {

        if (false === $this->_isDirty) {

            return false;
        }

        $query = $this->_buildSaveQuery($this->table(), $this->primary(), $this->_attributes);

        $updateBindings = $this->_attributes;

        if (isset($updateBindings[$this->primary()])) {

            unset($updateBindings[$this->primary()]);
        }

        $id = $this->_db->store($query, array_merge(array_values($this->_attributes), array_values($updateBindings)));

        if ($id) {

            $this->setAttribute($this->primary(), $id);

            $this->_isDirty = false;
        }

        return (int)$id;
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

        $id = isset($this->_attributes[$this->primary()]) ? $this->_attributes[$this->primary()] : null;

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
     * @return Model
     */
    public function setAttributes(array $data): Model
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
     * @param IDataTransformer|null $transformer
     * @return Model
     * @throws ModelException
     */
    public function setAttribute(string $name, $value, ?IDataTransformer $transformer = null): Model
    {
        if (false === in_array($name, $this->fields())) {

            throw new ModelException(ModelException::ERROR_INVALID_FIELD, $name);
        }

        if ($value instanceof Model) {

            $value = $value->primaryValue();
        }

        $value = null === $transformer ? $value : $transformer->transform($value);

        if (false === isset($this->_attributes[$name]) || $this->_attributes[$name] !== $value) {

            $this->_isDirty = true;
        }

        $this->_attributes[$name] = $value;

        return $this;
    }

    /**
     *
     * Get specific field value
     *
     * @param $name
     * @param IDataTransformer|null $transformer
     * @return mixed|null
     */
    public function getAttribute($name, ?IDataTransformer $transformer = null)
    {

        $value = isset($this->_attributes[$name]) ? $this->_attributes[$name] : null;

        return null === $transformer ? $value : $transformer->transform($value);
    }

    /**
     *
     * Get bulk of field values
     *
     * @param array $attributes
     * @return array
     */
    public function getAttributes(array $attributes): array
    {

        return array_intersect_key(array_flip($attributes), $this->_attributes);
    }

    public function toArray(): array
    {

        return $this->_attributes;
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

        $fields = array_keys($attributes);

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

            return null;
        }


        $resultModels = [];

        foreach ($results as $result) {

            $resultModels[] = new $this($result);
        }

        return $resultModels;
    }

    /**
     * @return string Table name
     */
    public abstract function table(): string;

    /**
     * @return string Name of primary key field
     */
    public abstract function primary(): string;

    /**
     * @return array Fields (columns) of table
     */
    public abstract function fields(): array;

}