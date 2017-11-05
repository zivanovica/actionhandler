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
    protected $_triggerCallbacks;

    public function __construct()
    {

        $this->_events = [];
        $this->_prepared = [];
        $this->_triggerCallbacks = [];
    }

    /**
     * Fire all events that were "triggered" during request handling
     */
    public function fire(): void
    {

        foreach ($this->_prepared as $eventData) {

            /** @var Event $event */
            $event = $this->_events[$eventData[Dispatcher::PARAM_EVENT_NAME]];

            /** @var array $data */
            $data = $eventData[Dispatcher::PARAM_EVENT_DATA];

            if ($event->isCanceled()) {

                continue;
            }

            call_user_func_array([$event, 'execute'], $data);
        }
    }

    /**
     * @param string $name
     * @param callable $callback
     */
    public function onTrigger(string $name, callable $callback): void
    {

        if (false === is_array($this->_triggerCallbacks[$name])) {

            $this->_triggerCallbacks[$name] = [];
        }

        $this->_triggerCallbacks[$name][] = $callback;
    }

    /**
     *
     * Prevent event from executing
     *
     * @param string $name
     */
    public function cancelEvent(string $name): void
    {

        if (false === isset($this->_events[$name])) {

            throw new DispatcherException(DispatcherException::ERROR_EVENT_NOT_FOUND, $name);
        }

        $this->_events[$name]->cancel();
    }

    /**
     *
     * Emits event with given name
     *
     * @param string $name
     * @param array $data
     * @return void
     */
    public function trigger(string $name, ... $data): void
    {

        if (false === isset($this->_events[$name])) {

            throw new DispatcherException(DispatcherException::ERROR_EVENT_NOT_FOUND, $name);
        }

        $this->_prepared[] = [
            Dispatcher::PARAM_EVENT_NAME => $name, Dispatcher::PARAM_EVENT_DATA => $data
        ];
    }

    /**
     *
     * Registers event under given name which makes it available for emitting
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
}