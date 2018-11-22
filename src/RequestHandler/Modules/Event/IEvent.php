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
     *
     * Execute event callback
     *
     * @param array ...$data
     * @return bool|null
     */
    public function execute(... $data): ?bool;

    /**
     *
     * Retrieve event name
     *
     * @return string
     */
    public function getName(): string;
}