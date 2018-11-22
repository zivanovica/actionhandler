<?php

namespace RequestHandler\Utils\DataFilter\Filters;

use RequestHandler\Utils\DataFilter\IDataFilter;

/**
 * Filter that is used to convert value to string
 *
 * @package Core\CoreUtils\DataFilter\Filters
 */
class StringFilter implements IDataFilter
{

    /** @var int|null */
    private $_maxLength = null;

    public function __construct(?int $maxLength = null)
    {

        $this->_maxLength = $maxLength;
    }

    /**
     *
     * Filters input value to string
     *
     * @param mixed $value
     * @return string
     */
    public function filter($value)
    {

        $value = is_string($value) ? $value : (string)$value;

        return null === $this->_maxLength ? $value : substr($value, 0, $this->_maxLength);
    }
}