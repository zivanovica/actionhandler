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

    /** @var \Closure */
    private $callback;

    /** @var string */
    private $name;

    /**
     * Event constructor.
     * @param null|string $name
     * @param callable $callback
     */
    public function __construct(string $name, callable $callback)
    {
        [$this->callback, $this->name] = [$callback, $name];
    }

    /**
     *
     * Execute event callback
     *
     * @param array ...$data
     * @return bool|null
     */
    public function execute(...$data): ?bool
    {
        array_unshift($data, $this);

        return call_user_func_array([\Closure::fromCallable($this->callback), 'call'], $data);
    }

    /**
     *
     * Retrieve event name
     *
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}
