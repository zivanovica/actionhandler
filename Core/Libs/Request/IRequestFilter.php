<?php

namespace Core\Libs\Request;

use Core\CoreUtils\DataFilter\IDataFilter;

/**
 *
 * This class is used to define request input filters, so when getting request value it get filtered properly to wanted type
 *
 * e.g ID parameter to User model
 *
 * @package Core\Libs\Request
 */
interface IRequestFilter
{

    /**
     *
     * Adds another filter to list for given field
     *
     * @param string $field
     * @param IDataFilter $filter
     * @return $this|IRequestFilter
     */
    public function add(string $field, IDataFilter $filter): IRequestFilter;

    /**
     *
     * Gets array of all filters for given field
     *
     * @param string $field
     * @return array|null
     */
    public function get(string $field): ?array;

    /**
     *
     * Checks does field contain any filters or not
     *
     * @param string $field
     * @return bool
     */
    public function hasFilter(string $field): bool;

    /**
     *
     * Will execute all filters for given field
     *
     * @param string $field Fields which filters should be applied
     * @param mixed $value Value that needs to be filtered
     * @return mixed
     */
    public function filter(string $field, $value);
}