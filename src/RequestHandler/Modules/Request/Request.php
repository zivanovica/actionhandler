<?php

namespace RequestHandler\Modules\Request;

use RequestHandler\Modules\Request\RequestFilter\IRequestFilter;
use RequestHandler\Modules\Router\IRouter;
use RequestHandler\Modules\Router\Router;
use RequestHandler\Utils\DataFilter\IDataFilter;
use RequestHandler\Utils\ObjectFactory\ObjectFactory;

/**
 *
 * Request class is here to hold all required request input values (GET, POST, PUT, PATCH, DELETE), also current request method
 *
 * @package Core\Libs\Request
 */
class Request implements IRequest
{


    /** @var string Holds current method identifier e.g GET */
    private $method;

    /** @var array Holds all query (GET) parameters */
    private $query;

    /** @var array Holds all body data (POST) parameters */
    private $data;

    /** @var Router */
    private $router;

    /** @var IRequestFilter */
    private $filter;

    /**
     *
     * Holds Route Parameters, Query Parameters and Form Body Data as merged array
     *
     * @var array
     */
    private $all;

    public function __construct()
    {
        $this->method = filter_input(
            INPUT_SERVER, 'REQUEST_METHOD', FILTER_DEFAULT, ['options' => ['default' => 'GET']]
        );

        $this->query = filter_input_array(INPUT_GET);
        $this->data = filter_input_array(INPUT_POST);

        if (empty($this->data)) {
            parse_str(file_get_contents('php://input'), $this->data);
        }

        $this->router = ObjectFactory::create(IRouter::class);
    }

    /**
     * @return string Current request method
     */
    public function method(): string
    {
        return $this->method;
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
        return $this->getFilterValue($key, $this->query[$key] ?? $default, $dataFilter);
    }

    /**
     *
     * Retrieve specific parameter value from url
     *
     * @param string $key
     * @param mixed|null $default
     * @param IDataFilter|null $dataFilter
     * @return null|mixed
     */
    public function parameter(string $key, $default = null, ?IDataFilter $dataFilter = null): ?array
    {
        return $this->getFilterValue($key, $this->getParameters()[$key] ?? $default, $dataFilter);
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
        return $this->getFilterValue($key, $this->data[$key] ?? $default, $dataFilter);
    }

    /**
     *
     * Get all POST body data
     *
     * @return array
     */
    public function getData(): array
    {
        return $this->data;
    }

    /**
     *
     * Get all url query data
     *
     * @return array
     */
    public function getQuery(): array
    {
        return $this->query;
    }

    /**
     *
     * Retrieve all URL parameters values
     *
     * @return array
     */
    public function getParameters(): array
    {
        $route = $this->router->currentRoute();

        return $route ? $route->parameters() : [];
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
        return $this->getFilterValue($key, $this->getAll()[$key] ?? $default, $dataFilter);
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
        if (false === is_array($this->all)) {
            $this->all = array_merge(
                $this->getParameters() ?? [], $this->query ?? [], $this->data ?? []
            );
        }

        return $this->all;
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
        $this->filter = $filter;

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
    private function getFilterValue(string $field, $value = null, ?IDataFilter $additionalFilter = null)
    {
        if ($additionalFilter) {
            $value = $additionalFilter->filter($value);
        }

        if (null === $this->filter || false === $this->filter->hasFilter($field)) {
            return $value;
        }

        return $this->filter->filter($field, $value);
    }
}