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
     * @param null|callable $trigger
     */
    public function __construct($initialValue, ?string $valueType = null, ?callable $trigger = null)
    {
        $this->valueType = $valueType;

        $this->set($initialValue);

        if ($trigger) {
            $this->subscriptions[] = $trigger;
        }
    }

    /**
     * @inheritdoc
     */
    public function set($value): void
    {
        if (null === $this->valueType || null === $value || $value instanceof $this->valueType) {

            [$value, $this->value] = [$this->value, $value];

            $this->trigger($value);

            return;
        }

        $type = is_object($value) ? get_class($value) : gettype($value);

        throw new ObservableException(ObservableException::INVALID_VALUE_TYPE, "Expect {$this->valueType} got {$type}");
    }

    /**
     * @inheritdoc
     */
    public function get()
    {
        return $this->value;
    }

    /**
     * @inheritdoc
     */
    public function subscribe(callable $callback, ?string $id = null): callable
    {
        $index = $id ?? count($this->subscriptions);

        $this->subscriptions[$index] = $callback;

        return function () use ($index) {
            $this->unsubscribe($index);
        };
    }

    /**
     * @inheritdoc
     */
    public function unsubscribe(string $id): void
    {
        if (isset($this->subscriptions[$id])) {
            unset($this->subscriptions[$id]);
        }
    }

    /**
     * @inheritdoc
     */
    public function clear(): void
    {
        $this->subscriptions = [];
    }

    /**
     * @inheritdoc
     */
    private function trigger($oldValue = null): void
    {
        /** @var callable $callback */
        foreach ($this->subscriptions ?? [] as $callback) {
            $callback($this->value, $oldValue);
        }
    }
}