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

class Builder implements IBuilder
{

    /** @var string */
    protected $_queryType = IBuilder::QUERY_NONE;

    /** @var array */
    protected $_values;

    /** @var string */
    protected $_table;

    protected $_fields;

    public function insert(string $tableName): IBuilder
    {
        $this->preventTypeAbuse();

        $this->_queryType = IBuilder::QUERY_INSERT;

        $this->_table = $tableName;

        return $this;
    }

    public function values(array $fields, array $values): IBuilder
    {
        $this->preventTypeAbuse(IBuilder::QUERY_INSERT);

        $this->_fields = $fields;
        $this->_values = $values;

        return $this;
    }

    public function reset(): IBuilder
    {
        $this->_queryType = IBuilder::QUERY_NONE;
        $this->_values = null;
        $this->_table = null;
        $this->_fields = null;

        return $this;
    }

    public function build(array $attributes = []): IQueryBuilder
    {
        $query = null;

        switch ($this->_queryType) {
            case IBuilder::QUERY_INSERT:

                $query = new InsertQueryBuilderBuilder($this->_table, $this->_fields, $this->_values, isset($attributes[IBuilder::ATTR_MULTI]) ?? $attributes[IBuilder::ATTR_MULTI]);

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
                BuilderException::ERR_BUILDER_OVERRIDE,
                "Expected {$expectedType} got {$this->_queryType}"
            );
        }

        if (IBuilder::QUERY_NONE !== $this->_queryType) {
            throw new BuilderException(BuilderException::ERR_BUILDER_OVERRIDE, 'Query building started');
        }
    }
}
