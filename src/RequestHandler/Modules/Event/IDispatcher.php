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
     * @return int
     */
    public function trigger(string $name, ... $data): int;

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
    public function preventEvent(int $handle): void;
}