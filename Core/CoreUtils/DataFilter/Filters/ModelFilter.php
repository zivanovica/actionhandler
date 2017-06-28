<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/21/17
 * Time: 3:17 PM
 */

namespace Core\CoreUtils\DataFilter\Filters;


use Core\CoreUtils\DataFilter\IDataFilter;
use Core\CoreUtils\Singleton;
use Core\Exceptions\ModelTransformerException;
use Core\Libs\Model\Model;

class ModelFilter implements IDataFilter
{
    use Singleton;

    /** @var array */
    private static $_cached = [];

    /** @var string */
    private $_modelName;

    /** @var string */
    private $_field;

    private function __construct(string $modelName, ?string $field = null)
    {

        $this->_modelName = $modelName;

        $this->_field = $field;
    }

    public function filter($value)
    {

        $uniqueId = hash('sha256', "{$this->_modelName}-{$this->_field}-{$value}");

        if (false === isset(self::$_cached[$uniqueId])) {

            /** @var Model $model */
            $model = new $this->_modelName();

            if (false === $model instanceof Model) {

                throw new ModelTransformerException(ModelTransformerException::ERROR_INVALID_MODEL_CLASS, $this->_modelName);
            }

            self::$_cached[$uniqueId] = empty($this->_field) ? $model->find($value) : $model->findOneWhere([$this->_field => $value]);
        }

        return self::$_cached[$uniqueId];
    }
}