<?php

namespace RequestHandler\Modules\Request\RequestFilter;

use RequestHandler\Utils\DataFilter\IDataFilter;

/**
 *
 * This class is used to define request input filters, so when getting request value it get filtered properly to wanted type
 *
 * e.g ID parameter to User model
 *
 * @package Core\Libs\Request
 */
class RequestFilter implements IRequestFilter
{


    /** @var array */
    private $_fields;

    /** @var array */
    private $_filtered;

    /**
     *
     * Adds another filter to list for given field
     *
     * @param string $field
     * @param IDataFilter $filter
     * @return $this|IRequestFilter
     */
    public function add(string $field, IDataFilter $filter): IRequestFilter
    {

        if (false === isset($this->_fields[$field])) {

            $this->_fields[$field] = [];
        }

        $this->_fields[$field][] = $filter;

        return $this;
    }

    /**
     *
     * Gets array of all filters for given field
     *
     * @param string $field
     * @return array|null
     */
    public function get(string $field): ?array
    {

        return isset($this->_fields[$field]) ? $this->_fields[$field] : null;
    }

    /**
     *
     * Checks does field contain any filters or not
     *
     * @param string $field
     * @return bool
     */
    public function hasFilter(string $field): bool
    {

        return false === empty($this->_fields[$field]);
    }

    /**
     *
     * Will execute all filters for given field
     *
     * @param string $field
     * @param mixed $value Value that needs to be filtered
     * @return mixed
     */
    public function filter(string $field, $value)
    {

        if (empty($this->_filtered[$field]) && $this->hasFilter($field)) {

            /** @var IDataFilter $filter */
            foreach ($this->_fields[$field] as $filter) {

                $value = $filter->filter($value);
            }
        }

        $this->_filtered[$field] = $value;

        return $this->_filtered[$field];
    }
}