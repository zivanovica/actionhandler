<?php
/**
 * Created by IntelliJ IDEA.
 * User: Coa
 * Date: 10/29/2018
 * Time: 11:26 PM
 */

namespace RequestHandler\Utils\Observable;

use RequestHandler\Exceptions\ObservableException;

class Observable implements IObservable
{

    /** @var mixed */
    private $value;

    /** @var array */
    private $subscriptions = [];

    /** @var string */
    private $valueType;

    /**
     * Observable constructor.
     * @param mixed $initialValue
     * @param null|string $valueType
     */
    public function __construct($initialValue, ?string $valueType = null)
    {
        $this->valueType = $valueType;

        $this->set($initialValue);
    }

    /**
     * @inheritdoc
     */
    public function set($value): void
    {
        if (null === $this->valueType || null === $value || $value instanceof $this->valueType) {
            [$value, $this->value] = [$this->value, $value];

            $this->trigger(ObservableEvent::EVENT_MODIFIED, $value);

            return;
        }

        $type = is_object($value) ? get_class($value) : gettype($value);

        throw new ObservableException(
            ObservableException::INVALID_VALUE_TYPE, "Expect {$this->valueType} got {$type}"
        );
    }

    /**
     * @inheritdoc
     */
    public function get()
    {
        $this->trigger(ObservableEvent::EVENT_ACCESSED, $this->value);

        return $this->value;
    }

    /**
     * @inheritdoc
     */
    public function subscribe(callable $callback, ?int $event = ObservableEvent::EVENT_MODIFIED): callable
    {
        if (false === is_array($this->subscriptions[$event] ?? null)) {
            $this->subscriptions[$event] = [];
        }

        $index = count($this->subscriptions[$event]);
        $this->subscriptions[$event][$index] = $callback;

        return function () use ($event, $index) {
            $this->unsubscribe($event, $index);
        };
    }

    /**
     * @inheritdoc
     */
    public function clear(?int $event = null): void
    {
        if (isset($this->subscriptions[$event])) {
            $this->subscriptions[$event] = [];

            return;
        }

        $this->subscriptions = [];
    }

    /**
     * @inheritdoc
     */
    private function unsubscribe(int $event,int $index): void
    {
        if (isset($this->subscriptions[$event][$index])) {
            unset($this->subscriptions[$event][$index]);
        }
    }

    /**
     * @inheritdoc
     */
    private function trigger(string $event, $oldValue = null): void
    {
        if (empty($this->subscriptions[$event])) {
            return;
        }

        /** @var callable $callback */
        foreach ($this->subscriptions[$event] as $callback) {
            if (false === is_callable($callback)) {
                continue;
            }

            $callback($oldValue, $this);
        }
    }

    /**
     * @inheritdoc
     */
    public function __toString()
    {
        return (string) $this->get();
    }
}