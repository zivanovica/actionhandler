<?php
/**
 * Created by IntelliJ IDEA.
 * User: coaps
 * Date: 4.11.2017.
 * Time: 01.14
 */

namespace RequestHandler\Modules\Event;

interface IDispatcher
{

    /**
     *
     * Add new event to dispatcher
     *
     * @param IEvent $event
     * @return IDispatcher
     */
    public function register(IEvent $event): IDispatcher;

    /**
     *
     * Adds event created using provided handler and name
     *
     * @param string $name
     * @param callable $handle
     * @return IDispatcher
     */
    public function registerCallable(string $name, callable $handle): IDispatcher;

    /**
     *
     * Queue event with given input  parameters
     *
     * @param string $name
     * @param array ...$data
     * @return callable By calling this method trigger will be ignored
     */
    public function trigger(string $name, ... $data): callable;

    /**
     *
     * Register callback that is triggered after event is triggered
     *
     * @param string $name
     * @param callable $callback
     * @return callable Unsubscribe method
     */
    public function subscribe(string $name, callable $callback): callable;

    /**
     * Fire all queued events
     */
    public function fire(): void;

    /**
     *
     * Prevent event with given name to be executed
     *
     * @param int $handleId
     */
    public function prevent(int $handleId): void;
}