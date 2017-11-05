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

    public function fire(): void;

    public function trigger(string $name, ... $data): void;

    public function onTrigger(string $name, callable $callback): void;

    public function cancelEvent(string $name): void;

    public function register(Event $event): IDispatcher;
}