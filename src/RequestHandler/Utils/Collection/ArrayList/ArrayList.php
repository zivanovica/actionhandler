<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RequestHandler\Utils\Collection\ArrayList;

use RequestHandler\Utils\Collection\Hash\Hash;

/**
 * Description of ArrayList
 *
 * @author Coa
 */
class ArrayList extends Hash implements IArrayList
{

    public function __construct(string $valueType = \object::class, int $capacity = PHP_INT_MAX)
    {
        parent::__construct(\int::class, $valueType, $capacity);
    }

    /**
     * Sort list using user defined method.
     *
     * @param callable $sort
     * @return void
     */
    public function sort(callable $sort): void
    {
        $values = $this->toArray();

        uasort($values, $sort);

        $this->values = $values;
        $this->keys = array_keys($values);
    }

    /**
     * Unlike "Hash", array list may not have key when defining a value.
     * Therefor we first determine is key provided or not, if not then we
     * generate next list key and execute inherited method for setting.
     *
     * @param type $key
     * @param type $value
     * @return void
     */
    public function offsetSet($key, $value): void
    {
        if (null === $key) {
            $key = empty($this->values) ? 0 : array_key_last($this->values) + 1;
        }

        parent::offsetSet($key, $value);
    }

}
