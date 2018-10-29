<?php
/**
 * Created by IntelliJ IDEA.
 * User: Coa
 * Date: 10/29/2018
 * Time: 11:24 PM
 */

namespace RequestHandler\Utils\Observable;

interface IObservable
{
    /**
     * Sets value of observable variable
     *
     * @param $value
     * @return void
     */
    public function set($value): void;

    /**
     * Retrieves value of observable variable
     *
     * @return mixed
     */
    public function get();

    /**
     * Register callback that will be triggered when ever observable value changes
     *
     * @param callable $callback
     * @param null|string $id
     * @return bool
     */
    public function subscribe(callable $callback, ?string $id = null): bool;

    /**
     * Remove subscription trigger with provided id
     *
     * @param string $id
     */
    public function unsubscribe(string $id): void;

    /**
     * Clear all subscription trigger callbacks
     */
    public function clear(): void;
}