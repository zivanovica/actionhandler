<?php

namespace RequestHandler\Modules\Database;

use RequestHandler\Exceptions\DatabaseException;

/**
 *
 *  Holds connection to MySQL database, and provides facade for fetching, storing and removing to and from database
 *
 * @package Core\Libs
 */
class Database implements IDatabase
{

    /** @var \PDO */
    public $connection;

    /**
     * @param string $host Host address
     * @param string $database Database name
     * @param string $username Username
     * @param string $password Password
     * @param int $port Port
     */
    public function __construct(string $host, string $database, string $username, string $password, int $port = 3306)
    {

        $dsn = "mysql:host={$host};port={$port};dbname={$database};";

        $this->connection = new \PDO($dsn, $username, $password);
    }

    /**
     *
     * Fetch single record from database
     *
     * @param string $query Query that will be executed
     * @param array $bindings Parameters that will be bind to query
     * @return array|null
     */
    public function fetch(string $query, array $bindings = []): ?array
    {

        $statement = $this->_executeQuery($query, $bindings);

        $results = $statement->fetch(\PDO::FETCH_ASSOC);

        return is_array($results) ? $results : null;
    }

    /**
     *
     * Fetch multiple record from database
     *
     * @param string $query Query that will be executed
     * @param array $bindings Parameters that will be bind to query
     * @return array|null
     */
    public function fetchAll(string $query, array $bindings = []): ?array
    {

        $statement = $this->_executeQuery($query, $bindings);

        $results = $statement->fetchAll(\PDO::FETCH_ASSOC);

        return is_array($results) ? $results : null;
    }


    /**
     *
     * Save record to database and retrieve its id
     * (INSERT, UPDATE queries)
     *
     * @param string $query Query that will be executed
     * @param array $bindings Parameters that will be bind to query execution
     * @return int
     */
    public function store(string $query, array $bindings): int
    {

        $statement = $this->_executeQuery($query, $bindings);

        return $this->connection->lastInsertId() ? $this->connection->lastInsertId() : $statement->rowCount();
    }

    /**
     *
     * Execute query and return number of affected rows
     *
     * Meant to be used with delete queries
     *
     * @param string $query
     * @param array $bindings
     * @return int
     */
    public function delete(string $query, array $bindings): int
    {

        $statement = $this->_executeQuery($query, $bindings);

        return $statement->rowCount();
    }

    /**
     *
     * Execute query and retrieve PDOStatement
     *
     * @param string $query Query that needs to be prepared and executed
     * @param array $bindings Parameters that will be bind on query
     * @return \PDOStatement
     * @throws DatabaseException
     */
    private function _executeQuery(string $query, array $bindings): \PDOStatement
    {

        $statement = $this->connection->prepare($query);

        if (false === $statement) {

            throw new DatabaseException(DatabaseException::ERROR_PREPARING_QUERY, "[{$statement->errorCode()}] {$query}");
        }

        if (false === $statement->execute($bindings)) {

            throw new DatabaseException(DatabaseException::ERROR_EXECUTING_QUERY, "[{$statement->errorCode()}] {$query}");
        }

        return $statement;
    }

}