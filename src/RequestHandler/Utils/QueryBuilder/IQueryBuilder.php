<?php
/**
 * Created by IntelliJ IDEA.
 * User: aleksandar
 * Date: 11/20/17
 * Time: 10:30 PM
 */

namespace RequestHandler\Utils\QueryBuilder;


interface IQueryBuilder
{

    public function getQuery(): string;

    public function getBindings(): array;
}