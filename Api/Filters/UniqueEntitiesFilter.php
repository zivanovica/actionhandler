<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 7/3/17
 * Time: 7:12 PM
 */

namespace Api\Filters;


use Core\CoreUtils\DataFilter\IDataFilter;
use Core\CoreUtils\Singleton;
use Core\Libs\Database;
use Core\Libs\Model\Model;

class UniqueEntitiesFilter implements IDataFilter
{

    use Singleton;

    /** @var Database */
    private $_db;

    public function __construct()
    {

        $this->_db = Database::getSharedInstance();
    }

    /**
     * @param mixed $value Value that will be filtered
     * @return mixed Filtered value
     */
    public function filter($value)
    {

        $uniqueIds = explode('-', $value);

        $placeholders = implode(',', array_fill(0, count($uniqueIds), '?'));

        $results = $this->_db->fetchAll("SELECT * FROM `unique` WHERE `id` IN ({$placeholders});", $uniqueIds);

        if (empty($results)) {

            return null;
        }

        $entities = [];

        foreach ($results as $result) {

            /** @var Model $model */
            $model = (new $result['entity_model']);

            if (false === $model instanceof Model) {

                continue;
            }

            $entity = $model->find($result['entity_id']);

            unset($model);

            if (null === $entity) {

                continue;
            }

            $entities[$result['id']] = $entity;
        }

        return $entities;
    }
}