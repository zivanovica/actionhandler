<?php
/**
 * Created by IntelliJ IDEA.
 * User: aleksandar
 * Date: 11/11/17
 * Time: 11:53 PM
 */

namespace RequestHandler\Modules\Entity;


interface IRepository
{

    /**
     *
     * Retrieve table name
     *
     * @return string
     */
    public function table(): string;

    /**
     *
     * Retrieves single model that matches given criteria
     *
     * @param string $query
     * @param array $bindings
     * @return IModel|null
     */
    public function find(string $query, array $bindings = []): ?IModel;

    /**
     *
     * Retrieves array of model instances that matched given search criteria
     *
     * @param string $query
     * @param array $bindings
     * @return array
     */
    public function findAll(string $query, array $bindings = []): array;

    /**
     *
     * Will queue model for inserting
     *
     * @param IModel $model
     * @return mixed
     */
    public function persist(IModel $model): void;

    /**
     *
     * Save all queued items to database
     *
     */
    public function flush(): void;
}