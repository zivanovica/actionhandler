<?php


namespace RequestHandler\Modules\Response;


use RequestHandler\Exceptions\ResponseException;

interface IResponse
{
    /**
     *
     * Set single response data value
     *
     * @param string $key
     * @param $value
     * @return IResponse
     */
    public function addData(string $key, $value): IResponse;

    /**
     *
     * Bulk set response data
     *
     * @param array $data
     * @return IResponse
     */
    public function data(array $data): IResponse;

    /**
     *
     * Retrieve all response data
     *
     * @return array
     */
    public function getData(): array;

    /**
     *
     * Set single response error
     *
     * @param string $error
     * @param string $message
     * @return IResponse
     */
    public function addError(string $error, string $message): IResponse;

    /**
     *
     * Bulk set response errors
     *
     * @param array $errors
     * @return IResponse
     */
    public function errors(array $errors): IResponse;

    /**
     *
     * Retrieve all response errors
     *
     * @return array
     */
    public function getErrors(): array;

    /**
     *
     * Checks does any error in response is set
     *
     * @return bool
     */
    public function hasErrors(): bool;

    /**
     *
     * Checks does certain error in response exists
     *
     * @param string $error
     * @return bool
     */
    public function hasError(string $error): bool;

    /**
     *
     * Set response status code
     *
     * @param int $status
     * @return IResponse
     * @throws ResponseException
     */
    public function status(int $status): IResponse;

    /**
     *
     * Retrieve current response status code
     *
     * @return int
     */
    public function getStatus(): int;

    /**
     *
     * Retrieve all response headers in format ["HEADER"=>"VALUE"]
     *
     * @return array
     */
    public function getHeaders(): array;

    /**
     *
     * Set headers
     *
     * @param array $headers
     */
    public function setHeaders(array $headers): void;

    /**
     * Set single header
     *
     * @param string $header
     * @param $value
     */
    public function setHeader(string $header, $value): void;
}