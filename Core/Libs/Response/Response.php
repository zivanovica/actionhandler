<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/14/17
 * Time: 3:10 PM
 */

namespace Core\Libs\Response;


use Core\CoreUtils\Singleton;
use Core\Exceptions\ResponseException;

class Response
{

    use Singleton;

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
     * @return Response
     */
    public function addData(string $key, $value): Response
    {

        $this->_data[$key] = $value;

        return $this;
    }

    /**
     *
     * Bulk set response data
     *
     * @param array $data
     * @return Response
     */
    public function data(array $data): Response
    {

        $this->_data = $data;

        return $this;
    }

    /**
     *
     * Set single response error
     *
     * @param string $error
     * @param string $message
     * @return Response
     */
    public function addError(string $error, string $message): Response
    {

        $this->_errors[$error] = $message;

        return $this;
    }

    /**
     *
     * Bulk set response errors
     *
     * @param array $errors
     * @return Response
     */
    public function errors(array $errors): Response
    {

        $this->_errors = $errors;

        return $this;
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
     * @return Response
     * @throws ResponseException
     */
    public function status(int $status): Response
    {

        if (false === isset(Response::$_commonStatusCodes[$status])) {

            throw new ResponseException(ResponseException::ERROR_INVALID_STATUS_CODE);
        }

        $this->_status = $status;

        return $this;
    }

    /**
     *
     * Finish request with given status, data and/or errors
     *
     * @param array $data
     * @param array $errors
     * @param int $status
     */
    public function send(array $data = [], array $errors = [], int $status = 200): void
    {

        empty($errors) ? $this->errors($errors) : $this->data($data);

        $this->status($status);

        $this->end();


    }

    /**
     * Finish request
     */
    public function end(): void
    {

        http_response_code($this->_status);

        echo json_encode([
            'status' => $this->hasErrors() ? Response::STATUS_ERROR : Response::STATUS_SUCCESS,
            'data' => $this->hasErrors() ? $this->_errors : $this->_data
        ]);
    }

}