<?php
/**
 * Created by IntelliJ IDEA.
 * User: aleksandar
 * Date: 11/20/17
 * Time: 10:54 PM
 */

namespace RequestHandler\Utils\QueryBuilder\Builders;


use RequestHandler\Utils\QueryBuilder\IQueryBuilder;

class InsertQueryBuilderBuilder implements IQueryBuilder
{

    private $_query;

    private $_bindings;

    public function __construct(string $table, array $fields, array $values, bool $multi)
    {

        $insertValues = $this->getInsertValues($values, $fields, $multi);
        $insertFields = implode('`,`', $fields);

        $this->_query = "INSERT INTO`{$table}` (`{$insertFields}`) VALUES {$insertValues}";
        $this->_bindings = $this->getInsertBindings($values, $fields, $multi);
    }

    public function getQuery(): string
    {

        return $this->_query;
    }

    public function getBindings(): array
    {

        return $this->_bindings;
    }

    private function getInsertValues(array $values, array $fields, bool $multi): string
    {

        $fieldsCount = count($fields);

        $result = array_fill(0, $fieldsCount, '?');
        $glue = ',';

        if ($multi) {

            $result = [];
            $glue = '),(';

            foreach ($values as $value) {

                $result[] = implode(',', array_fill(0, $fieldsCount, '?'));
            }

            unset($value);
        }

        return '(' . implode($glue, $result) . ')';
    }

    private function getInsertBindings(array $vals, array $fields, bool $multi): array
    {

        if (false === $multi) {

            return $this->getInsertBindingValues($vals, $fields);
        }

        $results = [];

        foreach ($vals as $values) {

            $results = array_merge($results, $this->getInsertBindings($values, $fields, false));
        }

        return $results;
    }

    private function getInsertBindingValues(array $values, array $fields): array
    {

        $results = [];

        foreach ($fields as $field) {

            if (isset($values[$field])) {

                $results[] = $values[$field];

                continue;
            }

            $results[] = null;
        }

        return $results;
    }
}