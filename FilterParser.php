<?php
/**
 * Created by IntelliJ IDEA.
 * User: Coa
 * Date: 10/12/2018
 * Time: 2:30 AM
 */

/**
 * Class FilterParser
 *
 * Input processing value: age-20-30+body_type-slim,fat+rate-gt4+votes-elt34
 *
 * gt -> >
 * lt -> <
 * egt -> =>
 * elt -> =<
 */
class FilterParser
{

    const DEFAULT_FILTER_SEPARATOR = '+';
    const DEFAULT_FILTER_VALUES_SEPARATOR = '-';
    const DEFAULT_FILTER_NAME_SEPARATOR = ':';

    private $filterSeparator = self::DEFAULT_FILTER_SEPARATOR;
    private $filterValuesSeparator = self::DEFAULT_FILTER_VALUES_SEPARATOR;
    private $filterNameSeparator = self::DEFAULT_FILTER_NAME_SEPARATOR;

    private $parsers = [];

    public function parse(string $value)
    {
        /**
         *  Collection of strings that represents filter name, and its parsers
         *  (e.g
         *      20-30 = range, lt20 = less than 20, egt40 = equal greater than 40
         *      lt20-30 range from less than 20 to 30 (from 0 to 30 :) )
         *
         * @var array $data
         */
        $data = explode($this->filterSeparator, $value);

        foreach ($data as $filter) {
            list($filterName, $filters) = explode($this->filterNameSeparator, $filter);

            foreach ($this->parsers as $parser) {
                if (false === $parser->match($filter)) {
                    continue;
                }

                var_dump($a->parse($filter));
            }
        }
    }

    /**
     
     */
    public function addParser(IFilterParser $parser): FilterParser
    {
        $this->parsers[] = $parser;

        return $this;
    }
}

abstract class BaseFilterParser implements IFilterParser
{
    protected $matches = [];

    /**
     * @return string Regular expression string used to match filter type
     */
    protected abstract function getRegularExpressionString(): string;

    /**
     * @return mixed Value provided by1
     */
    protected abstract function getValue();

    public function parse(string $filter)
    {
        if (false === $this->match($filter)) {
            return null;
        }

        return $this->getValue();
    }

    public function match(string $filter): bool
    {
        return (bool) preg_match($this->getRegularExpressionString(), $filter, $this->matches);
    }
}

class RangeFilterParser extends BaseFilterParser
{
    protected function getRegularExpressionString(): string
    {
        return '/(?<min>\d+)\-(?<max>\d+(\.\d+)?)/i';
    }

    protected function getValue()
    {
        return ['min' => (float) $this->matches['min'] ?? 0, 'max' => (float) $this->matches['max'] ?? 0];
    }
}

interface IFilterParser
{
    public function match(string $filter): bool;

    public function parse(string $filter);
}

interface IFilterParserOperation
{

    /**
     * Parse provided string value
     *
     * @param string $filters
     * @return bool
     */
    public function parse(string $filters): bool;

    /**
     * @return array|null
     */
    public function matches(): IFilterValueCollection;
}

interface IFilterValue
{
    /**
     * @return string
     */
    public function getFilterName(): string;

    /**
     * @return mixed
     */
    public function getFilterValue(): object;
}

interface IFilterValueCollection extends ArrayAccess, Iterator
{

}

abstract class BaseFilterParserOperation implements IFilterParserOperation
{
    protected $filterValueCollection;

    public function __construct()
    {

    }

    protected function addFilterValue(IFilterValue $filterValue): IFilterParserOperation
    {


        return $this;
    }
}

/**
 * Class LessThanFilterParserOperation
 *
 * Even though BaseFilterParserOperation implements interface IFilterParserOperation, we are implementing it
 * in actual class, so if any time later we decide to step away from BaseFilterParserOperation inheritance
 * we won't forget to do methods implementation as PHP itself will throw exceptions (and any IDE)
 *
 *
 */
class LessThanFilterParserOperation extends BaseFilterParserOperation implements IFilterParserOperation
{

    /**
     * Parse provided string value
     *
     * @param string $filters
     * @return bool
     */
    public function parse(string $filters): bool
    {
        // TODO: Implement parse() method.
    }

    /**
     * @return array|null
     */
    public function matches(): IFilterValueCollection
    {
        // TODO: Implement matches() method.
    }
}


$fp = new FilterParser();
$fp->parse('age:20-30+body_type:slim,fat+rate:gt4-elt6+votes:elt34');
