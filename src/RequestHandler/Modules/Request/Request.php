<?php

namespace RequestHandler\Modules\Request;

use RequestHandler\Modules\Request\RequestFilter\IRequestFilter;
use RequestHandler\Modules\Router\IRouter;
use RequestHandler\Utils\DataFilter\IDataFilter;
use RequestHandler\Utils\SingletonFactory\SingletonFactory;
use RequestHandler\Modules\Router\Router;

/**
 *
 * Request class is here to hold all required request input values (GET, POST, PUT, PATCH, DELETE), also current request method
 *
 * @package Core\Libs\Request
 */
class Request implements IRequest
{


    /** @var string Holds current method identifier e.g GET */
    private $_method;

    /** @var array Holds all query (GET) parameters */
    private $_query;

    /** @var array Holds all body data (POST) parameters */
    private $_data;

    /** @var Router */
    private $_router;

    /** @var IRequestFilter */
    private $_filter;

    /**
     *
     * Holds Route Parameters, Query Parameters and Form Body Data as merged array
     *
     * @var array
     */
    private $_all;

    public function __construct()
    {

        $this->_method = $_SERVER['REQUEST_METHOD'];

        $query = filter_input_array(INPUT_GET);

        $this->_query = null === $query ? [] : $query;

        $data = filter_input_array(INPUT_POST);

        if (empty($data) || false === is_array($data)) {

            parse_str(file_get_contents('php://input'), $data);
        }

        $this->_data = false === is_array($data) ? [] : $data;

        $this->_router = SingletonFactory::getSharedInstance(IRouter::class);

    }

    /**
     * @return string Current request method
     */
    public function method(): string
    {

        return $this->_method;
    }

    /**
     *
     * Retrieves query (GET) parameter matching given key
     *
     * @param string $key
     * @param mixed $default
     * @param IDataFilter|null $dataFilter
     * @return null|string
     */
    public function query(string $key, $default = null, ?IDataFilter $dataFilter = null)
    {
        return $this->_getFilterValue($key, isset($this->_query[$key]) ? $this->_query[$key] : $default, $dataFilter);
    }

    /**
     *
     * Retrieve specific parameter value from url
     *
     * @param string $key
     * @param mixed|null $default
     * @param IDataFilter|null $dataFilter
     * @return mixed
     */
    public function parameter(string $key, $default = null, ?IDataFilter $dataFilter = null)
    {

        $parameters = $this->_router->currentRoute()->parameters();

        return $this->_getFilterValue($key, isset($parameters[$key]) ? $parameters[$key] : $default, $dataFilter);
    }

    /**
     *
     * Retrieves body data (POST) parameter matching given key
     *
     * @param string $key
     * @param mixed $default
     * @param IDataFilter|null $dataFilter
     * @return mixed
     */
    public function data(string $key, $default = null, ?IDataFilter $dataFilter = null)
    {

        return $this->_getFilterValue($key, isset($this->_data[$key]) ? $this->_data[$key] : $default, $dataFilter);
    }

    /**
     *
     * Get all POST body data
     *
     * @return array
     */
    public function getData(): array
    {

        return $this->_data;
    }

    /**
     *
     * Get all url query data
     *
     * @return array
     */
    public function getQuery(): array
    {

        return $this->_query;
    }

    /**
     *
     * Retrieve all URL parameters values
     *
     * @return array
     */
    public function getParameters(): array
    {

        return $this->_router->currentRoute()->parameters();
    }

    /**
     *
     * Get value matching key in any of input arrays POST, GET, URL Parameters
     *
     * NOTE: In case that there are multiple keys with same name on different arrays e.g "id" in both URL and Form Body
     * following system of prioritizing is applied
     *  1. POST
     *  2. If not in POST look GET
     *  3. If not in none of above look in URL Parameters
     *
     * @param string $key
     * @param null $default
     * @param IDataFilter|null $dataFilter
     * @return mixed
     */
    public function get(string $key, $default = null, ?IDataFilter $dataFilter = null)
    {
        $all = $this->getAll();

        return $this->_getFilterValue($key, isset($all[$key]) ? $all[$key] : $default, $dataFilter);
    }

    /**
     *
     * Get all key => value pairs from any input POST, GET, URL Parameter
     *
     * NOTE: In case that there are multiple keys with same name on different arrays (e.g "id" in both URL and Form Body)
     * following system of prioritizing is applied
     *  1. POST
     *  2. If not in POST look GET
     *  3. If not in none of above look in URL Parameters
     *
     * @return array
     */
    public function getAll(): array
    {

        if (empty($this->_all)) {

            $this->_all = array_merge($this->getParameters(), $this->_query, $this->_data);
        }

        return $this->_all;
    }

    /**
     *
     * Set request input filter used to transform values to desire type/value
     *
     * @param IRequestFilter $filter
     * @return IRequest
     */
    public function setFilter(IRequestFilter $filter): IRequest
    {

        $this->_filter = $filter;

        return $this;
    }

    /**
     *
     * Executes all filters for given field, and return final value, in case that there are no filter original value is returned
     *
     * @param string $field
     * @param null $value
     * @param IDataFilter|null $additionalFilter
     * @return mixed|null
     */
    private function _getFilterValue(string $field, $value = null, ?IDataFilter $additionalFilter = null)
    {

        if ($additionalFilter) {

            $value = $additionalFilter->filter($value);
        }

        if (null === $this->_filter || false === $this->_filter->hasFilter($field)) {

            return $value;
        }

        return $this->_filter->filter($field, $value);
    }
}