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
    const ATTR_MULTI = 1;

    const QUERY_NONE = 0;
    const QUERY_INSERT = 1;
    const QUERY_SELECT = 2;
    const QUERY_UPDATE = 3;
    const QUERY_DELETE = 4;
    const QUERY_TRUNCATE = 5;

    public function insert(string $tableName): IBuilder;
}
