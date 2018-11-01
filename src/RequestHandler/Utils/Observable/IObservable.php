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
     * @param null|int $event
     * @return callable Unsubscribe function
     */
    public function subscribe(callable $callback, ?int $event = 0): callable;

    /**
     * Clear all subscription trigger callbacks
     *
     * @param null|int $event
     */
    public function clear(?int $event = null): void;

    /**
     * Return string value of observable value
     *
     * @return string
     */
    public function __toString();
}