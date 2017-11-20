<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 8/17/17
 * Time: 2:47 AM
 */

namespace RequestHandler\Modules\Model;


use RequestHandler\Exceptions\ModelException;
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
     * @return mixed
     */
    public function primaryValue();

    /**
     *
     * Retrieve value of model field (column)
     *
     * @param string $name
     * @param mixed $default
     * @return mixed
     */
    public function field(string $name, $default = null);

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
    public function setField(string $name, $value): IModel;

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
     * If method returns true model will be updated when repository flushes
     *
     * @return bool
     */
    public function isDirty(): bool;

    /**
     *
     * Model data with type of array
     *
     * @return array
     */
    public function toArray(): array;

    /**
     *
     * Hydrate model with new values
     *
     * @param array $data
     */
    public function hydrate(array $data): void;
}