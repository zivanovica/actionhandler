<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RequestHandler\Utils\Filter\Parser;

/**
 *
 * @author Coa
 */
interface ICollectionFilterParser
{

    /**
     *
     * @param string $filter
     * @return bool
     */
    public function isMatch(string $filter): bool;

    /**
     *
     * @param string $filter
     * @return array|null
     */
    public function parse(string $filter): ?array;
}
