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

    public function __construct(string $valueType, int $capacity = PHP_INT_MAX)
    {
        parent::__construct(\int::class, $valueType, $capacity);
    }

}
