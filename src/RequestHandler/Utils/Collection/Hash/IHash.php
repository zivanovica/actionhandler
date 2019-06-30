<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RequestHandler\Utils\Collection\Hash;

/**
 *
 * @author Coa
 */
interface IHash extends \ArrayAccess, \Iterator, \Countable
{

    /**
     * @return string Data type name of key
     */
    public function getKeyType(): string;

    /**
     * @return string Data type name of value
     */
    public function getValueType(): string;

    /**
     *
     * @return \RequestHandler\Utils\Collection\Hash\IHash
     */
    public function filter(callable $filter): IHash;

    public function map(callable $map): IHash;

    public function remove(callable $remove): void;

    public function toArray(): array;
}
