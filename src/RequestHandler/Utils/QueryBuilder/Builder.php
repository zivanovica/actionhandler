<?php
/**
 * Created by IntelliJ IDEA.
 * User: aleksandar
 * Date: 11/20/17
 * Time: 9:31 PM
 */

namespace RequestHandler\Utils\QueryBuilder;


use RequestHandler\Exceptions\BuilderException;
use RequestHandler\Utils\QueryBuilder\Builders\InsertQueryBuilderBuilder;

class Builder
{

    const QUERY_NONE = 0;
    const QUERY_INSERT = 1;
    const QUERY_SELECT = 2;
    const QUERY_UPDATE = 3;
    const QUERY_DELETE = 4;
    const QUERY_TRUNCATE = 5;

    /** @var string */
    protected $_queryType = Builder::QUERY_NONE;

    /** @var array */
    protected $_values;

    /** @var string */
    protected $_table;

    protected $_fields;

    /** @var bool */
    protected $_multi = false;

    public function insert(string $tableName, bool $multi = false): Builder
    {

        $this->preventTypeAbuse();

        $this->_queryType = Builder::QUERY_INSERT;

        $this->_table = $tableName;

        $this->_multi = $multi;

        return $this;
    }

    public function values(array $fields, array $values): Builder
    {

        $this->preventTypeAbuse(Builder::QUERY_INSERT);

        $this->_fields = $fields;
        $this->_values = $values;

        return $this;
    }

    public function reset(): Builder
    {

        $this->_queryType = Builder::QUERY_NONE;
        $this->_values = null;
        $this->_table = null;
        $this->_fields = null;

        return $this;
    }

    public function build(): IQueryBuilder
    {

        $query = null;

        switch ($this->_queryType) {
            case Builder::QUERY_INSERT:

                $query = new InsertQueryBuilderBuilder($this->_table, $this->_fields, $this->_values, $this->_multi);

                break;
        }

        return $query;
    }



    /**
     *
     * @param int $expectedType
     * @throws BuilderException|null
     */
    private function preventTypeAbuse(?int $expectedType = null): void
    {

        if (null !== $expectedType) {

            if ($expectedType === $this->_queryType) {

                return;
            }

            throw new BuilderException(
                BuilderException::ERR_BUILDER_OVERRIDE, "Expected {$expectedType} got {$this->_queryType}"
            );
        }

        if (Builder::QUERY_NONE !== $this->_queryType) {

            throw new BuilderException(BuilderException::ERR_BUILDER_OVERRIDE, 'Query building started');
        }
    }
}