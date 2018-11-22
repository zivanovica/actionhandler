<?php
/**
 * Created by IntelliJ IDEA.
 * User: aleksandar
 * Date: 11/21/17
 * Time: 10:39 AM
 */

namespace RequestHandler\Utils\QueryBuilder\Builders;


use RequestHandler\Utils\QueryBuilder\IQueryBuilder;

class SelectQueryBuilderBuilder implements IQueryBuilder
{

    public function __construct(string $table, array $criteria, array $join = [], ?array $fields)
    {
    }

    public function getQuery(): string
    {
        // TODO: Implement getQuery() method.
    }

    public function getBindings(): array
    {
        // TODO: Implement getBindings() method.
    }

    private function buildSelectQuery(string $tableName, array $criteria, array $join, array $fields): string
    {

        if (empty($fields)) {

            $fieldsString = '*';
        } else {

            $fieldsString = '`' . implode('`,`', $fields) . '`';
        }

        $query = "SELECT {$fieldsString} FROM `{$tableName}`";

        if (false === empty($criteria)) {

            $query = "{$query} WHERE {$this->buildWhereCriteria($criteria)}";
        }

        return $query;
    }

    private function buildWhereCriteria(array $criteria): string
    {



        /**
         * [
         *  'id' => 1, // `id` = ?
         *  'username' => ['!=', 'coa'], // `username` != ?
         * ]
         */

        foreach ($criteria as $fields => $criterion) {



        }
    }
}