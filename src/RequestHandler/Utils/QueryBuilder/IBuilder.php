<?php
/**
 * Created by IntelliJ IDEA.
 * User: aleksandar
 * Date: 11/20/17
 * Time: 9:31 PM
 */

namespace RequestHandler\Utils\QueryBuilder;

interface IBuilder
{

    public function insert(string $tableName): IBuilder;
}