<?php

namespace RequestHandler\Utils\DataFilter;

/**
 * Implement this interface if you are creating new filtering rule
 *
 * @package Core\CoreUtils\DataFilter
 */
interface IDataFilter
{

    /**
     * @param mixed $value Value that will be filtered
     * @return mixed Filtered value
     */
    public function filter($value);
}