<?php
/**
 * Created by IntelliJ IDEA.
 * User: coaps
 * Date: 6.11.2017.
 * Time: 00.00
 */

namespace RequestHandler\Modules\Event;


interface IEvent
{

    /**
     * Prevent event from executing
     */
    public function cancel(): void;

    /**
     * Retrieve flag that determines should event be executed or not
     */
    public function isCanceled(): bool;

    /**
     *
     * Execute event callback
     *
     * @return bool|null
     */
    public function execute(): ?bool;

    /**
     *
     * Retrieve event name
     *
     * @return string
     */
    public function getName(): string;
}