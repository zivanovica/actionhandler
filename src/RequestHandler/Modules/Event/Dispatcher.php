<?php
/**
 * Created by IntelliJ IDEA.
 * User: coaps
 * Date: 4.11.2017.
 * Time: 00.49
 */

namespace RequestHandler\Modules\Event;

use RequestHandler\Exceptions\DispatcherException;

class Dispatcher implements IDispatcher
{

    private const PARAM_EVENT_NAME = 0;
    private const PARAM_EVENT_DATA = 1;

    /** @var Event[] */
    protected $_events;

    /** @var array */
    protected $_prepared;

    /** @var array */
    protected $_subscription;

    public function __construct()
    {

        $this->_events = [];
        $this->_prepared = [];
        $this->_subscription = [];
    }

    /**
     *
     * Add new event to dispatcher
     *
     * @param Event $event
     * @return IDispatcher
     */
    public function register(Event $event): IDispatcher
    {

        if (isset($this->_events[$event->getName()])) {

            return $this;
        }

        $this->_events[$event->getName()] = $event;

        return $this;
    }

    /**
     *
     * Register callback that is triggered after event is triggered
     *
     * @param string $name
     * @param callable $callback
     * @return callable Unsubscribe method
     */
    public function subscribe(string $name, callable $callback): callable
    {

        if (false === isset($this->_events[$name])) {

            throw new DispatcherException(DispatcherException::ERR_EVENT_NOT_FOUND, $name);
        }

        if (false === is_array($this->_subscription[$name])) {

            $this->_subscription[$name] = [];
        }

        $index = count($this->_subscription[$name]);

        $this->_subscription[$name][$index] = $callback;

        return function () use ($name, $index): bool {

            if (false === isset($this->_subscription[$name]) || false === isset($this->_subscription[$name][$index])) {

                return false;
            }

            unset($this->_subscription[$name][$index]);

            return true;
        };
    }

    /**
     *
     * Queue event with given input  parameters
     *
     * @param string $name
     * @param array ...$data
     * @return int
     */
    public function trigger(string $name, ... $data): int
    {

        if (false === isset($this->_events[$name])) {

            throw new DispatcherException(DispatcherException::ERR_EVENT_NOT_FOUND, $name);
        }

        $index = count($this->_prepared);

        $this->_prepared[$index] = [
            Dispatcher::PARAM_EVENT_NAME => $name, Dispatcher::PARAM_EVENT_DATA => $data
        ];

        return $index;
    }

    /**
     * Fire all queued events
     */
    public function fire(): void
    {

//        ob_start();

        foreach ($this->_prepared as $eventData) {

            // Event prevented
            if (null === $eventData) {

                continue;
            }

            /** @var Event $event */
            $event = $this->_events[$eventData[Dispatcher::PARAM_EVENT_NAME]];

            call_user_func_array([$event, 'execute'], $eventData[Dispatcher::PARAM_EVENT_DATA]);

            if (false === isset($this->_subscription[$eventData[Dispatcher::PARAM_EVENT_NAME]])) {

                continue;
            }

            /** @var callable $callback */
            foreach ($this->_subscription[$eventData[Dispatcher::PARAM_EVENT_NAME]] as $callback) {

                $callback($eventData[Dispatcher::PARAM_EVENT_DATA]);
            }
        }


//        ob_end_clean();
    }

    /**
     *
     * Prevent event with given name to be executed
     *
     * @param int $handle
     */
    public function prevent(int $handle): void
    {

        if (false === isset($this->_prepared[$handle])) {

            throw new DispatcherException(DispatcherException::ERR_BAD_EVENT_HANDLE, $handle);
        }

        $this->_prepared[$handle] = null;
    }
}