<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/16/17
 * Time: 2:59 PM
 */

namespace Core\CoreUtils\DataFilter\Filters;


use Core\CoreUtils\DataFilter\IDataFilter;

class WaterfallFilter implements IDataFilter
{

    /**
     * @var IDataFilter[]
     */
    private $_transformers;

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
     * @param IDataFilter[] $dataTransformers
     */
    public function __construct(array $dataTransformers)
    {

        $this->_transformers = $dataTransformers;
    }

    public function filter($value)
    {

        foreach ($this->_transformers as $transformer) {

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