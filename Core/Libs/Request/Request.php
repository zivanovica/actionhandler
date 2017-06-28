<?php
/**
 * Created by IntelliJ IDEA.
 * User: Aleksandar Zivanovic
 * Email: coapsyfactor@gmail.com
 * Date: 6/14/17
 * Time: 2:58 PM
 */

namespace Core\Libs\Request;

use Api\Models\Token;
use Core\CoreUtils\DataFilter\IDataFilter;
use Core\CoreUtils\Singleton;
use Core\Libs\Router\Router;

class Request
{

    use Singleton;

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

    /** @var Token */
    private $_token;

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

        $this->_router = Router::getSharedInstance();

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
     * @return null|string
     */
    public function query(string $key, $default = null)
    {
        return $this->_getFilterValue($key, isset($this->_query[$key]) ? $this->_query[$key] : $default);
    }

    /**
     *
     * Retrieve specific parameter value from url
     *
     * @param string $key
     * @param null $default
     * @return mixed
     */
    public function parameter(string $key, $default = null)
    {

        $parameters = $this->_router->currentRoute()->parameters();

        return $this->_getFilterValue($key, isset($parameters[$key]) ? $parameters[$key] : $default);
    }

    /**
     *
     * Retrieves body data (POST) parameter matching given key
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function data(string $key, $default = null)
    {

        return $this->_getFilterValue($key, isset($this->_data[$key]) ? $this->_data[$key] : $default);
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
     * @return mixed
     */
    public function get(string $key, $default = null)
    {
        $all = $this->getAll();

        return $this->_getFilterValue($key, isset($all[$key]) ? $all[$key] : $default);
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
     * Set valid token for current request
     *
     * @param Token $token
     * @return Request
     */
    public function setToken(Token $token): Request
    {

        $this->_token = $token;

        return $this;
    }

    /**
     *
     * Get current request token
     *
     * @return Token|null
     */
    public function token(): ?Token
    {

        return $this->_token;
    }

    /**
     *
     * Set request input filter used to transform values to desire type/value
     *
     * @param IRequestFilter $filter
     * @return Request
     */
    public function setFilter(IRequestFilter $filter): Request
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
     * @return mixed|null
     */
    private function _getFilterValue(string $field, $value = null)
    {

        if (null === $this->_filter || false === $this->_filter->hasFilter($field)) {

            return $value;
        }

        return $this->_filter->filter($field, $value);
    }
}