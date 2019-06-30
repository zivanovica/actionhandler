<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RequestHandler\Utils\Collection\Hash;

/**
 * Description of Hash
 *
 * @author Coa
 */
class Hash implements IHash
{

    private const KEY = 0;
    private const VALUE = 1;

    /** @var string[] List of primitive types */
    private static $primitiveTypess = [\string::class => true, \int::class => true, \bool::class => true];

    /** @var array Flags for determination primitive key or value */
    private $primitive;

    /** @var string Data type of array key */
    protected $keyType;

    /** @var string Data type of array value */
    protected $valueType;

    /** @var array Map of all actual keys */
    protected $keys;

    /** @var array List array of available values */
    protected $values;

    /** @var int Maximum number of elements in Hash */
    protected $capacity;

    /**
     *
     * @param string $keyType Type of key
     * @param string $valueType
     * @param int $capacity
     */
    public function __construct(
        string $keyType = \object::class, string $valueType = \object::class, int $capacity = PHP_INT_MAX)
    {
        if (0 > $capacity) {
            throw new \RuntimeException('Capacity may not be less than 0');
        }

        $this->keyType = $keyType;
        $this->valueType = $valueType;

        if ('array' === $this->keyType) {
            throw new \RuntimeException('Key type may not be type of "array"');
        }

        $this->values = [];
        $this->keys = [];
        $this->capacity = $capacity;

        $this->primitive = [
          self::KEY => self::isPrimitive($this->keyType), self::VALUE => self::isPrimitive($this->valueType)
        ];
    }

    /**
     * @return string Data type name of key
     */
    public function getKeyType(): string
    {
        return $this->keyType;
    }

    /**
     * @return string Data type name of value
     */
    public function getValueType(): string
    {
        return $this->valueType;
    }

    /**
     * Retrieve new hash list containing only keys/values for which callback function returned "true"
     *
     * @param callable $filter
     * @return \RequestHandler\Utils\Collection\Hash\IHash
     */
    public function filter(callable $filter): IHash
    {
        $hash = new Hash($this->getKeyType(), $this->getValueType());

        foreach ($this as $key => $value) {
            if (true === $filter($value, $key, $this)) {
                $hash[$key] = $value;
            }
        }

        return $hash;
    }

    /**
     * Retrieve new hash list of same type, containing values that are returned from callback function.
     *
     * @param callable $map
     * @return \RequestHandler\Utils\Collection\Hash\IHash
     */
    public function map(callable $map): IHash
    {
        $hash = new Hash($this->getKeyType(), $this->getValueType());

        foreach ($this as $key => $value) {
            $hash[$key] = $map($value, $key, $this);
        }

        return $hash;
    }

    /**
     * This method will remove all key/values for which callback method return "true"
     *
     * @param callable $remove
     * @return void
     */
    public function remove(callable $remove): void
    {
        $removingKeys = [];

        foreach ($this as $key => $value) {
            $remove($value, $key, $this) && $removingKeys[] = $key;
        }

        foreach ($removingKeys as $key) {
            unset($this[$key]);
        }
    }

    /**
     * Check if provided data type is a primitive one
     *
     * @param string $type
     * @return bool
     */
    public static function isPrimitive(string $type): bool
    {
        return self::$primitiveTypess[$type] ?? false;
    }

    /**
     *
     * Retrieve real offset identifier
     *
     * @param mixed $offset
     * @return string Offset assoc key
     */
    private function getKeyIdentifier($offset): string
    {
        $keyType = self::getDataType($offset);

        return ($this->primitive[self::KEY] || (\object::class === $this->keyType && self::isPrimitive($keyType))) ?
            (string) $offset :
            (is_bool($offset) ? 'bool(' . ($offset ? 'true' : 'false') . ')' : spl_object_hash($offset));
    }

    /**
     *
     * @param type $value
     * @param string $type
     * @param bool $isPrimitive
     * @param string $errorMessage
     * @return bool
     */
    private static function isValidType($value, string $type, bool $isPrimitive): bool
    {
        if (\object::class === $type) {
            return true;
        }

        $checkingType = $isPrimitive ? self::getDataType($value) : $value;

        return (false === $isPrimitive && $value instanceof $type) || ($isPrimitive && $checkingType === $type);
    }

    /**
     * @param mixed $variable
     * @return string Data type
     */
    public static function getDataType($variable): string
    {
        $type = gettype($variable);

        return $type === 'integer' ? \int::class : $type;
    }

    public function toArray(): array
    {
        return $this->values;
    }

    public function offsetUnset($offset): void
    {
        $key = $this->getKeyIdentifier($offset);

        unset($this->values[$key], $this->keys[$key]);
    }

    public function offsetExists($offset): bool
    {
        return isset($this->values[$this->getKeyIdentifier($offset)]);
    }

    public function offsetGet($offset)
    {
        return $this->values[$this->getKeyIdentifier($offset)] ?? null;
    }

    /**
     * Validates offset key and offset value.
     * If types are correct no exception will be thrown
     *
     * @param mixed $key
     * @param mixed $value
     * @return void
     * @throws \RuntimeException
     */
    public function offsetSet($key, $value): void
    {
        if (count($this->values) >= $this->capacity) {
            throw new \RuntimeException("Maximum capacity of '{$this->capacity}' reached.");
        }

        if (null === $key) {
            throw new \RuntimeException('Key must be provided.');
        }

        [$keyType, $valueType] = [self::getDataType($key), self::getDataType($value)];

        [self::KEY => $primitiveKey, self::VALUE => $primitiveValue] = $this->primitive;

        if (false === self::isValidType($key, $this->keyType, $primitiveKey)) {
            throw new \RuntimeException("Invalid key type. Expected {$this->keyType} got {$keyType}");
        }

        if (false === self::isValidType($value, $this->valueType, $primitiveValue)) {
            throw new \RuntimeException("Invalid value type. Expected {$this->valueType} got {$valueType}");
        }

        $keyId = $this->getKeyIdentifier($key);

        [$this->keys[$keyId], $this->values[$keyId]] = [$key, $value];
    }

    public function current()
    {
        return current($this->values);
    }

    /**
     * @return mixed Original key instead of object hash
     */
    public function key()
    {
        return $this->keys[key($this->values)];
    }

    public function next(): void
    {
        next($this->values);
    }

    public function rewind(): void
    {
        reset($this->values);
    }

    public function valid(): bool
    {
        return isset($this->values[key($this->values)]);
    }

    public function count(): int
    {
        return count($this->values);
    }

    /**
     * Overriding default action when "var_dump" or "print_r" are executed upon this object.
     *
     * @return array
     */
    public function __debugInfo()
    {
        $debugInfo = [];

        foreach ($this->keys as $key => $actualKey) {
            $type = self::getDataType($actualKey);
            $name = is_object($actualKey) ? get_class($actualKey) . "({$key})" : $key;

            $debugInfo["{$type} {$name}"] = $this->values[$key];
        }

        return $debugInfo;
    }

}
