<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 8/17/17
 * Time: 2:41 PM
 */

namespace RequestHandler\Modules\Request;


use RequestHandler\Modules\Request\RequestFilter\IRequestFilter;
use RequestHandler\Utils\DataFilter\IDataFilter;

interface IRequest
{

    /**
     * @return string Current request method
     */
    public function method(): string;

    /**
     *
     * Retrieves query (GET) parameter matching given key
     *
     * @param string $key
     * @param mixed $default
     * @param IDataFilter|null $dataFilter
     * @return null|string
     */
    public function query(string $key, $default = null, ?IDataFilter $dataFilter = null);

    /**
     *
     * Retrieve specific parameter value from url
     *
     * @param string $key
     * @param mixed|null $default
     * @param IDataFilter|null $dataFilter
     * @return mixed
     */
    public function parameter(string $key, $default = null, ?IDataFilter $dataFilter = null);

    /**
     *
     * Retrieves body data (POST) parameter matching given key
     *
     * @param string $key
     * @param mixed $default
     * @param IDataFilter|null $dataFilter
     * @return mixed
     */
    public function data(string $key, $default = null, ?IDataFilter $dataFilter = null);

    /**
     *
     * Get all POST body data
     *
     * @return array
     */
    public function getData(): array;

    /**
     *
     * Get all url query data
     *
     * @return array
     */
    public function getQuery(): array;

    /**
     *
     * Retrieve all URL parameters values
     *
     * @return array
     */
    public function getParameters(): array;

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
    public function get(string $key, $default = null, ?IDataFilter $dataFilter = null);

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
    public function getAll(): array;

    /**
     *
     * Set request input filter used to transform values to desire type/value
     *
     * @param IRequestFilter $filter
     * @return IRequest
     */
    public function setFilter(IRequestFilter $filter): IRequest;
}