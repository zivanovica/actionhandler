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
    public function __construct(string $keyType, string $valueType, int $capacity = PHP_INT_MAX)
    {
        $this->keyType = $keyType;
        $this->valueType = $valueType;

        if ('array' === $this->keyType) {
            throw new \RuntimeException('Key type may not be type of "array"');
        }

        $this->values = [];
        $this->keys = [];
        $this->capacity = $capacity;

        $this->primitive = [
          Hash::KEY => Hash::isPrimitiveType($this->keyType),
          Hash::VALUE => Hash::isPrimitiveType($this->valueType)
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
     * Check if provided data type is a primitive one
     *
     * @param string $type
     * @return bool
     */
    public static function isPrimitiveType(string $type): bool
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
    private function getOffsetIdentifier($offset): string
    {
        return $this->primitive[self::KEY] ? ((string) $offset) : spl_object_hash($offset);
    }

    /**
     *
     * @param type $value
     * @param string $keyType
     * @param bool $isPrimitive
     * @param string $errorMessage
     * @return void
     * @throws \RuntimeException
     */
    private static function validateType($value, string $type, bool $isPrimitive, string $errorMessage): void
    {
        $checkingType = self::getDataType($value);

        if (
            (false === $isPrimitive && false === $value instanceof $type) ||
            ($isPrimitive && $checkingType !== $type)
        ) {
            throw new \RuntimeException($errorMessage);
        }
    }

    public static function getDataType($variable): string
    {
        $type = gettype($variable);

        return $type === 'integer' ? \int::class : $type;
    }

    public function offsetUnset($offset): void
    {
        $key = $this->getOffsetIdentifier($offset);

        unset($this->values[$key], $this->keys[$key]);

        $this->rewind();
    }

    public function offsetExists($offset): bool
    {
        return isset($this->values[$this->getOffsetIdentifier($offset)]);
    }

    public function offsetGet($offset)
    {
        return $this->values[$this->getOffsetIdentifier($offset)] ?? null;
    }

    public function offsetSet($offset, $value): void
    {
        $keyType = self::getDataType($offset);

        self::validateType(
            $offset,
            $this->keyType,
            $this->primitive[self::KEY],
            "Invalid key data type. Expected {$this->keyType} got {$keyType}"
        );

        $valueType = self::getDataType($value);

        self::validateType(
            $value,
            $this->valueType,
            $this->primitive[self::VALUE],
            "Invalid value data type. Expected {$this->valueType} got {$valueType}"
        );
//
//        if (
//            (false === $this->primitive[self::KEY] && false === $offset instanceof $this->keyType) ||
//            ($this->primitive[self::KEY] && $keyType !== $this->keyType)
//        ) {
//            throw new \RuntimeException("Invalid key data type. Expected {$this->keyType} got {$keyType}");
//        }
//
//        $valueType = self::getDataType($value);
//
//        if (
//            (false === $this->primitive[self::VALUE] && false === $value instanceof $this->valueType) ||
//            ($this->primitive[self::VALUE] && $valueType !== $this->valueType)
//        ) {
//            throw new \RuntimeException("Invalid value data type. Expected {$this->valueType} got {$valueType}");
//        }

        if (count($this->values) >= $this->capacity) {
            throw new \RuntimeException("Maximum capacity of '{$this->capacity}' reached.");
        }

        $key = $this->getOffsetIdentifier($offset);

        $this->keys[$key] = $offset;
        $this->values[$key] = $value;
    }

    public function current()
    {
        return current($this->values);
    }

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

}
