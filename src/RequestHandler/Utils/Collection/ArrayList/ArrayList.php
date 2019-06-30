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

    public function sort(callable $sort): void
    {
        $values = $this->toArray();

        foreach ($values as $index => $value) {
            $sorting = $sort($value, $index);

            if (1 === $sorting) {
                [$values[$index + 1], $values[$index]] = [$value, $values[$index + 1]];
            } else if (-1 === $sorting) {
                [$values[$index - 1], $values[$index]] = [$value, $values[$index - 1]];
            }
        }
        /**
         * @todo FINISH THIS PROPERLY!
         */
//        $this->values = $
    }

    public function offsetSet($key, $value): void
    {
        if (null === $key) {
            $key = empty($this->values) ? 0 : array_key_last($this->values) + 1;
        }

        parent::offsetSet($key, $value);
    }

}
