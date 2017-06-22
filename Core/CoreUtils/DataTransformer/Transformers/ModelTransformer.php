<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/21/17
 * Time: 3:17 PM
 */

namespace Core\CoreUtils\DataTransformer\Transformers;


use Core\CoreUtils\DataTransformer\IDataTransformer;
use Core\CoreUtils\Singleton;
use Core\Exceptions\ModelTransformerException;
use Core\Libs\Model\Model;

class ModelTransformer implements IDataTransformer
{
    use Singleton;

    /** @var string */
    private $_modelName;

    /** @var string */
    private $_field;

    private function __construct(string $modelName, ?string $field = null)
    {

        $this->_modelName = $modelName;

        $this->_field = $field;
    }

    public function transform($value)
    {

        /** @var Model $model */
        $model = new $this->_modelName();

        if (false === $model instanceof Model) {

            throw new ModelTransformerException(ModelTransformerException::ERROR_INVALID_MODEL_CLASS, $this->_modelName);
        }

        return empty($this->_field) ? $model->find($value) : $model->findOneWhere([$this->_field => $value]);

    }
}