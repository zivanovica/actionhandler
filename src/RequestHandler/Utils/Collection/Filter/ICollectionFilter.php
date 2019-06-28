<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

namespace RequestHandler\Utils\Filter;

/**
 * Description of ICollectionFilter
 *
 * @author Coa
 */
interface ICollectionFilter
{

    /**
     *
     */
    public function parse(string $filters): array;
}
