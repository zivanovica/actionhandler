<?php
/**
 * Created by IntelliJ IDEA.
 * User: Aleksandar Zivanovic
 * Email: coapsyfactor@gmail.com
 * Date: 6/14/17
 * Time: 2:58 PM
 */

namespace Core\Libs;

use Core\CoreUtils\DataTransformer\IDataTransformer;
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

    public function __construct()
    {

        $this->_method = $_SERVER['REQUEST_METHOD'];

        $query = filter_input_array(INPUT_GET);

        $this->_query = null === $query ? [] : $query;

        $data = filter_input_array(INPUT_POST);

        $this->_data = null === $data ? [] : $data;

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
     * @param IDataTransformer $transformer
     * @return null|string
     */
    public function query(string $key, $default = null, IDataTransformer $transformer = null)
    {
        return $this->_getTransformedValue($transformer, isset($this->_query[$key]) ? $this->_query[$key] : $default);
    }

    public function parameter(string $key, $default = null, IDataTransformer $transformer = null)
    {

        $parameters = $this->_router->currentRoute()->parameters();

        return $this->_getTransformedValue($transformer, isset($parameters[$key]) ? $parameters[$key] : $default);
    }

    /**
     *
     * Retrieves body data (POST) parameter matching given key
     *
     * @param string $key
     * @param mixed $default
     * @param IDataTransformer $transformer
     * @return mixed
     */
    public function data(string $key, $default = null, IDataTransformer $transformer = null)
    {

        return $this->_getTransformedValue($transformer, isset($this->_data[$key]) ? $this->_data[$key] : $default);
    }

    public function allData(): array
    {

        return $this->_data;
    }

    public function allQuery(): array
    {

        return $this->_query;
    }

    /**
     *
     * Retrieve transformed value if transformator is set, otherwise return original value
     *
     * @param IDataTransformer $transformer
     * @param $value
     * @return mixed
     */
    private function _getTransformedValue(?IDataTransformer $transformer, $value)
    {

        if (null === $transformer) {

            return $value;
        }

        return $transformer->transform($value);
    }

}