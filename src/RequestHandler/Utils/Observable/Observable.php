<?php
/**
 * Created by IntelliJ IDEA.
 * User: Coa
 * Date: 10/29/2018
 * Time: 11:26 PM
 */

namespace RequestHandler\Utils\Observable;

class Observable implements IObservable
{
    /** @var mixed */
    private $value;

    /** @var string */
    private $objectId;

    /** @var array */
    private $subscriptions = [];

    /**
     * Observable constructor.
     * @param mixed $initialValue
     * @param null|callable $trigger
     */
    public function __construct($initialValue, ?callable $trigger = null)
    {
        $this->value = $initialValue;
        $this->objectId = spl_object_hash($this);

        if ($trigger) {
            $this->subscriptions[] = $trigger;
        }
    }

    /**
     * @inheritdoc
     */
    public function set($value): void
    {
        [$value, $this->value] = [$this->value, $value];

        $this->trigger($value);
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
    public function getId(): string
    {
        return $this->objectId;
    }

    /**
     * @inheritdoc
     */
    public function subscribe(callable $callback, ?string $id = null): bool
    {
        $this->subscriptions[$id ?? count($this->subscriptions)] = $callback;

        return true;
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
        $this->subscriptions[$this->objectId] = [];
    }

    /**
     * @inheritdoc
     */
    private function trigger($oldValue = null): void
    {
        if (empty($this->subscriptions)) {
            return;
        }

        /** @var callable $callback */
        foreach ($this->subscriptions as $callback) {
            $callback($this->value, $oldValue);
        }
    }
}