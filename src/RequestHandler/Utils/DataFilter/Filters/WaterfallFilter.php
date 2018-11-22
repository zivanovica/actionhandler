<?php

namespace RequestHandler\Utils\DataFilter\Filters;


use RequestHandler\Utils\DataFilter\IDataFilter;

/**
 * This data transformer accepts array of data transformers and transform value through each one in order they are present
 *
 * @package Core\CoreUtils\DataFilter\Filters
 */
class WaterfallFilter implements IDataFilter
{

    /**
     * @var IDataFilter[]
     */
    private $_filters;

    /**
     *
     * This data transformer accepts array of data transformers and transform value through each one in order they are present
     *
     * e.g
     *
     * new WaterfallTransformer([new IntTransformer(), new StringTransformer()]) with given value of '1' will do following
     *
     * 1st step - transform to integer
     * 2nd step - transformed value transform back to string
     *
     * return last transformed value
     *
     * @param IDataFilter[] $dataFilters
     */
    public function __construct(array $dataFilters)
    {

        $this->_filters = $dataFilters;
    }

    /**
     *
     * Filters input value through all given filters
     *
     * @param mixed $value
     * @return mixed
     */
    public function filter($value)
    {

        foreach ($this->_filters as $transformer) {

            $this->_transform($transformer, $value);
        }

        return $value;
    }

    /**
     * @param IDataFilter $transformer
     * @param $value
     */
    private function _transform(?IDataFilter $transformer, &$value)
    {

        if (null === $transformer) {

            return;
        }

        $value = $transformer->filter($value);
    }
}