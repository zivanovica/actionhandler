<?php

namespace RequestHandler\Modules\Response;

use RequestHandler\Utils\ObjectFactory\ObjectFactory;
use RequestHandler\Exceptions\ResponseException;
use RequestHandler\Modules\Model\Model;

/**
 * This is used to set up response data, such as status code, data, errors, headers etc..
 *
 * TODO: Add headers handling
 *
 * @package Core\Libs\Response
 */
class Response implements IResponse
{



    const STATUS_SUCCESS = 'succes';

    const STATUS_ERROR = 'error';

    /** @var array Array of all available status codes, filled dynamic when "Response" constructor is called */
    private static $_commonStatusCodes;

    private $_status = 200;

    private $_data = [];

    private $_errors = [];

    private function __construct()
    {

        Response::$_commonStatusCodes = array_flip(
            (new \ReflectionClass(IResponseStatus::class))->getConstants()
        );
    }


    /**
     *
     * Set single response data value
     *
     * @param string $key
     * @param $value
     * @return IResponse
     */
    public function addData(string $key, $value): IResponse
    {

        if ($value instanceof Model) {

            $value = $value->toArray();
        }

        $this->_data[$key] = $value;

        return $this;
    }

    /**
     *
     * Bulk set response data
     *
     * @param array $data
     * @return IResponse
     */
    public function data(array $data): IResponse
    {

        $this->_data = [];

        foreach ($data as $key => $value) {

            $this->addData($key, $value);
        }

        return $this;
    }

    /**
     *
     * Retrieve all response data
     *
     * @return array
     */
    public function getData(): array
    {

        return $this->_data;
    }

    /**
     *
     * Set single response error
     *
     * @param string $error
     * @param string $message
     * @return IResponse
     */
    public function addError(string $error, string $message): IResponse
    {

        $this->_errors[$error] = $message;

        return $this;
    }

    /**
     *
     * Bulk set response errors
     *
     * @param array $errors
     * @return IResponse
     */
    public function errors(array $errors): IResponse
    {

        $this->_errors = $errors;

        return $this;
    }

    /**
     *
     * Retrieve all response errors
     *
     * @return array
     */
    public function getErrors(): array
    {

        return $this->_errors;
    }

    /**
     *
     * Checks does any error in response is set
     *
     * @return bool
     */
    public function hasErrors(): bool
    {

        return false === empty($this->_errors);
    }

    /**
     *
     * Checks does certain error in response exists
     *
     * @param string $error
     * @return bool
     */
    public function hasError(string $error): bool
    {

        return isset($this->_errors[$error]);
    }

    /**
     *
     * Set response status code
     *
     * @param int $status
     * @return IResponse
     * @throws ResponseException
     */
    public function status(int $status): IResponse
    {

        if (false === isset(Response::$_commonStatusCodes[$status])) {

            throw new ResponseException(ResponseException::ERROR_INVALID_STATUS_CODE);
        }

        $this->_status = $status;

        return $this;
    }

    /**
     *
     * Retrieve current response status code
     *
     * @return int
     */
    public function getStatus(): int
    {

        return $this->_status;
    }
}