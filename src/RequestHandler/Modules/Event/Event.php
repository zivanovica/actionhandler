<?php
/**
 * Created by IntelliJ IDEA.
 * User: coaps
 * Date: 5.11.2017.
 * Time: 23.48
 */

namespace RequestHandler\Modules\Event;


class Event implements IEvent
{

    /** @var callable */
    private $_callback;

    /** @var string */
    private $_name;

    /** @var bool */
    private $_isCanceled;

    /**
     * Event constructor.
     * @param null|string $name
     * @param callable $callback
     */
    public function __construct(string $name, callable $callback)
    {

        $this->_callback = $callback;
        $this->_name = $name;

        $this->_isCanceled = false;
    }

    /**
     * Prevent
     */
    public function cancel(): void
    {

        $this->_isCanceled = true;
    }

    public function isCanceled(): bool
    {

        return $this->_isCanceled;
    }

    public function execute(... $data): ?bool
    {

        $closure = \Closure::fromCallable($this->_callback);

        array_unshift($data, $this);

        return call_user_func_array([$closure, 'call'], $data);
    }

    public function getName(): string
    {

        return $this->_name;
    }
}