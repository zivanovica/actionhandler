<?php

namespace Bulletproof\Filters;

use RequestHandler\Modules\Database\IDatabase;
use RequestHandler\Utils\DataFilter\IDataFilter;
use RequestHandler\Utils\ObjectFactory\ObjectFactory;
use RequestHandler\Modules\Database\Database;
use RequestHandler\Modules\Model\Model;

class UniqueEntitiesFilter implements IDataFilter
{

    /** @var Database */
    private $_db;

    public function __construct()
    {

        $this->_db = ObjectFactory::create(IDatabase::class);
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