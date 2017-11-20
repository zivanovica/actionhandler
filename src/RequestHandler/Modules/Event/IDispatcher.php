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
     * @param Event $event
     * @return IDispatcher
     */
    public function register(Event $event): IDispatcher;

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
     * @param int $handle
     */
    public function prevent(int $handle): void;
}