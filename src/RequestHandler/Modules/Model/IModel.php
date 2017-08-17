<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 8/17/17
 * Time: 2:47 AM
 */

namespace RequestHandler\Modules\Model;


use RequestHandler\Exceptions\ModelException;
use RequestHandler\Utils\DataFilter\IDataFilter;
use RequestHandler\Utils\Decorator\Types\ITypedDecorator;

interface IModel
{
    const DEFAULT_PRIMARY_KEY = 'id';

    /**
     * @return string Name of primary ID value
     */
    public function primary(): string;

    /**
     * Retrieves value of primary key for current entity
     *
     * @param IDataFilter $transformer
     * @return mixed
     */
    public function primaryValue(?IDataFilter $transformer = null);

    /**
     * @return string Table/collection name
     */
    public function table(): string;

    /**
     *
     * Validates does model (entity) exists in storage
     *
     * @return bool
     */
    public function exists(): bool;

    /**
     *
     * List of all available fields (columns)
     *
     * @return array
     */
    public function fields(): array;

    /**
     *
     * Find and populate model with data using primary key and given value
     *
     * @param $primary
     * @return mixed
     */
    public function find($primary): IModel;

    /**
     *
     * Retrieve multiple entities with given criteria
     *
     * @param array $criteria
     * @return $this[]|Model[]|null
     */
    public function findWhere(array $criteria): array;

    /**
     *
     * Retrieve single entity with given criteria
     *
     * @param array $criteria
     * @return $this|IModel
     */
    public function findOneWhere(array $criteria): IModel;

    /**
     *
     * Get bulk of field values
     *
     * @param array $attributes
     * @return array
     */
    public function getAttributes(array $attributes): array;


    /**
     *
     * Get specific field value
     *
     * @param string $name
     * @param IDataFilter $filter
     * @return mixed|null
     */
    public function getAttribute(string $name, ?IDataFilter $filter = null);

    /**
     *
     * Set specific field value
     *
     * @param string $name Field name
     * @param mixed $value Value of field
     * @return $this|IModel
     * @throws ModelException
     */
    public function setAttribute(string $name, $value): IModel;

    /**
     *
     * Bulk set of model values
     *
     * @param array $data
     * @return $this|IModel
     */
    public function setAttributes(array $data): IModel;

    /**
     * @return bool
     */
    public function save(): bool;

    /**
     * Reset all changes to original values
     */
    public function reset(): void;

    /**
     * @return bool Is model dirty, should it be updated (saved)
     */
    public function dirty(): bool;

    /**
     *
     * Removes model (entity) from storage
     *
     * @return bool
     */
    public function delete(): bool;

    /**
     * @param string $decoratorClassName Class name of wanted decorator
     * @param array $decoratorParameters Parameters that will be passed to decorator constructor
     * @return ITypedDecorator
     */
    public function decorate($decoratorClassName, array $decoratorParameters = []): ITypedDecorator;

    /**
     *
     * Model data with type of array
     *
     * @return array
     */
    public function toArray(): array;
}