<?php
/**
 * Created by IntelliJ IDEA.
 * User: aleksandar
 * Date: 11/12/17
 * Time: 12:01 AM
 */

namespace RequestHandler\Modules\Entity;

use RequestHandler\Exceptions\RepositoryException;
use RequestHandler\Modules\Database\IDatabase;
use RequestHandler\Utils\ObjectFactory\ObjectFactory;
use RequestHandler\Utils\QueryBuilder\IBuilder;

/**
 *
 * TODO: Move query building logic to query builder
 *
 * @package RequestHandler\Modules\Model
 */
class Repository implements IRepository
{

    /** @var IDatabase */
    private $_db;

    /** @var string */
    private $_tableName;

    /** @var string */
    private $_modelClass;

    /** @var IModel[] */
    private $_models;

    /** @var IModel[] */
    private $_persistModels;

    public function __construct(string $modelClass)
    {
        if (false === class_exists($modelClass)) {
            throw new RepositoryException(RepositoryException::ERR_CLASS_NOT_FOUND, $modelClass);
        }

        if (false === is_subclass_of($modelClass, IModel::class)) {
            throw new RepositoryException(
                RepositoryException::ERR_CLASS_TYPE_MISMATCH,
                "Expected " . IModel::class . " got {$modelClass}"
            );
        }

        $this->_db = ObjectFactory::create(IDatabase::class);

        /** @var IModel $model */
        $this->_tableName = ObjectFactory::create($modelClass)->table();

        $this->_modelClass = $modelClass;

        $this->_models = [];

        $this->_persistModels = [];
    }

    /**
     * @return string
     */
    public function table(): string
    {
        return $this->_tableName;
    }

    /**
     *
     * Retrieves single model that matches given criteria
     *
     * @param string $query
     * @param array $bindings
     *
     * @return IModel|null
     *
     * @throws \ReflectionException
     */
    public function find(string $query, array $bindings = []): ?IModel
    {
        $query = "SELECT * FROM `{$this->table()}` WHERE {$query}";

        $result = $this->_db->fetch($query, $bindings);

        if (empty($result)) {
            return null;
        }

        /** @var IModel $model */
        $model = ObjectFactory::createNew($this->_modelClass);

        $model->hydrate($result);

        $this->_models[$model->primaryValue()] = $model;

        return $model;
    }

    /**
     *
     * Retrieves array of model instances that matched given search criteria
     *
     * @param string $query
     * @param array $bindings
     *
     * @return array
     *
     * @throws \ReflectionException
     */
    public function findAll(string $query, array $bindings = []): array
    {
        $query = "SELECT * FROM `{$this->table()}` WHERE {$query};";
        ;

        $results = $this->_db->fetchAll($query, $bindings);

        if (empty($results)) {
            return [];
        }

        $models = [];

        foreach ($results as $result) {

            /** @var IModel $model */
            $model = ObjectFactory::createNew($this->_modelClass);

            $model->hydrate($result);

            if (isset($this->_models[$model->primaryValue()])) {
                $model = $this->_models[$model->primaryValue()];
            }

            $models[$model->primaryValue()] = $model;
        }

        $this->_models += $models;

        return $models;
    }

    /**
     *
     * Queue model for inserting
     *
     * @param IModel $model
     */
    public function persist(IModel $model): void
    {
        if (false === is_a($model, $this->_modelClass)) {
            $modelClass = get_class($model);

            throw new RepositoryException(
                RepositoryException::ERR_CLASS_TYPE_MISMATCH,
                "Expected {$this->_modelClass} got {$modelClass}"
            );
        }

        if (in_array($model, $this->_persistModels)) {
            return;
        }

        $this->_persistModels[] = $model;
    }

    public function fill(array $models): void
    {
        $primary = ObjectFactory::create($this->_modelClass)->primary();

        foreach ($models as $model) {
            if (false === isset($model[$primary])) {
                continue;
            }

            $this->_models[$model[$primary]] = ObjectFactory::createNew($this->_modelClass, $model);
        }
    }

    /**
     *
     * Save all queued items to database
     *
     */
    public function flush(): void
    {
        $this->insert();

        $this->update();
    }

    public function toArray(): array
    {
        return $this->_toArray($this->_models);
    }

    private function _toArray(array $inputModels): array
    {
        $models = [];

        foreach ($inputModels as $model) {
            $models[] = $model->toArray();
        }

        return $models;
    }

    private function update(): void
    {

//        $query = "UPDATE {$this->table()} SET "
        foreach ($this->_models as $model) {

//            var_dump($model->isDirty());
        }
    }

    /**
     *
     * Inserts models that are queued for persist
     *
     * @throws \ReflectionException
     */
    private function insert(): void
    {
        if (false === empty($this->_persistModels)) {
            $query = ObjectFactory::create(IBuilder::class)
                ->insert($this->_persistModels[0]->table(), true)
                ->values(array_keys($this->_persistModels[0]->fields()), $this->_toArray($this->_persistModels))
                ->build([ IBuilder::ATTR_MULTI => 1 < count($this->_persistModels) ]);

            $this->_db->store($query);

            $this->_persistModels = [];
        }
    }
}
