<?php
/**
 * Created by IntelliJ IDEA.
 * User: coaps
 * Date: 4.11.2017.
 * Time: 00.49
 */

namespace RequestHandler\Modules\Event;

use RequestHandler\Exceptions\DispatcherException;
use RequestHandler\Utils\ObjectFactory\ObjectFactory;

class Dispatcher implements IDispatcher
{

    private const PARAM_EVENT_NAME = 0;
    private const PARAM_EVENT_DATA = 1;

    /** @var Event[] */
    protected $events = [];

    /** @var array */
    protected $prepared = [];

    /** @var array */
    protected $subscription = [];

    /** Prevent direct instantiating */
    private function __construct()
    {
        register_tick_function([&$this, 'executeEventQueue'], true);
    }

    public function executeEventQueue(): bool
    {
        $this->fire();

        return true;
    }

    /**
     *
     * Add new event to dispatcher
     *
     * @param IEvent $event
     * @return IDispatcher
     */
    public function register(IEvent $event): IDispatcher
    {
        if (false === isset($this->events[$event->getName()])) {
            $this->events[$event->getName()] = $event;
        }

        return $this;
    }

    /**
     *
     * Adds event created using provided handler and name
     *
     * @param string $name
     * @param callable $handle
     * @return IDispatcher
     */
    public function registerCallable(string $name, callable $handle): IDispatcher
    {
        return $this->register(
            new class ($name, $handle) implements IEvent
            {

                private $name;
                private $handle;

                public function __construct(string $name, callable $handle)
                {
                    $this->name = $name;
                    $this->handle = $handle;
                }

                public function execute(...$data): ?bool
                {
                    return call_user_func_array($this->handle, $data);
                }

                public function getName(): string
                {
                    return $this->name;
                }
            }
        );
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
        if (false === isset($this->events[$name])) {
            throw new DispatcherException(DispatcherException::EVENT_NOT_FOUND, $name);
        }

        if (false === is_array($this->subscription[$name] ?? null)) {
            $this->subscription[$name] = [];
        }

        $index = (array_key_last($this->subscription[$name]) ?? -1) + 1;

        $this->subscription[$name][$index] = $callback;

        return function () use ($name, $index): bool {
            if (false === isset($this->subscription[$name][$index])) {
                return false;
            }

            unset($this->subscription[$name][$index]);

            return true;
        };
    }

    /**
     *
     * Queue event with given input  parameters
     *
     * @param string $name
     * @param array ...$data
     * @return callable
     */
    public function trigger(string $name, ...$data): callable
    {
        if (false === isset($this->events[$name])) {
            throw new DispatcherException(DispatcherException::EVENT_NOT_FOUND, $name);
        }

        $index = (array_key_last($this->prepared) ?? -1) + 1;

        $this->prepared[$index] = [
            Dispatcher::PARAM_EVENT_NAME => $name, Dispatcher::PARAM_EVENT_DATA => $data
        ];

        return function () use ($index): void {
            $this->prevent($index);
        };
    }

    /**
     * Fire all queued events
     */
    public function fire(): void
    {
        ob_start();

        foreach ($this->prepared as [Dispatcher::PARAM_EVENT_NAME => $name, Dispatcher::PARAM_EVENT_DATA => $data]) {
            /** @var Event $event */
            $event = $this->events[$name];

            call_user_func_array([$event, 'execute'], $data);

            /** @var callable $callback */
            foreach ($this->subscription[$name] ?? [] as $callback) {
                if (is_callable($callback)) {
                    $callback($data);
                }
            }
        }

        $this->prepared = [];

        ob_end_clean();
    }

    /**
     *
     * Prevent event with given handleId to be executed
     *
     * @param int $handleId
     */
    public function prevent(int $handleId): void
    {
        if (false === isset($this->prepared[$handleId])) {
            throw new DispatcherException(DispatcherException::BAD_EVENT_HANDLE, "{$handleId}");
        }

        unset($this->prepared[$handleId]);
    }
}
