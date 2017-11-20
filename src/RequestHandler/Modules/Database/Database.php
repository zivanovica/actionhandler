<?php

namespace RequestHandler\Modules\Database;

use RequestHandler\Exceptions\DatabaseException;
use RequestHandler\Modules\Application\IApplication;

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

    /** @var string */
    private $_dsn;

    /** @var string */
    private $_username;

    /** @var string */
    private $_password;

    /** @var IApplication */
    private $_application;

    /**
     * @param IApplication $application
     */
    public function __construct(IApplication $application)
    {


        $this->_application = $application;
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

        $this->connect();

        $statement = $this->connection->prepare($query);

        if (false === $statement) {

            throw new DatabaseException(DatabaseException::ERR_PREPARING_QUERY, "[{$statement->errorCode()}] {$query}");
        }

        if (false === $statement->execute($bindings)) {

            throw new DatabaseException(DatabaseException::ERR_EXECUTING_QUERY, "[{$statement->errorCode()}] {$query}");
        }

        return $statement;
    }

    /**
     * Connects to database if connection property doesn't hold instance of \PDO
     */
    private function connect(): void
    {

        if ($this->connection instanceof \PDO) {

            return;
        }

        $config = $this->_application->config();

        if (false === isset($config['database'])) {

            throw new DatabaseException(DatabaseException::ERR_BAD_PARAMETERS, 'database');
        }

        $this->validateConnectionParameters($config['database']);

        $username = $config['database']['username'];

        $password = $config['database']['password'];

        $database = $config['database']['dbname'];

        $host = isset($config['database']['host']) ? $config['database']['host'] : 'localhost';

        $port = isset($config['database']['port']) ? $config['database']['port'] : 3306;

        $dsn = "mysql:host={$host};port={$port};dbname={$database};";

        $this->connection = new \PDO($dsn, $username, $password);
    }

    /**
     *
     * Validates existence of all parameters required for establishing connection to database
     *
     * @param array $config
     * @throws DatabaseException
     */
    private function validateConnectionParameters(array $config): void
    {

        if (false === isset($config['username'])) {

            throw new DatabaseException(DatabaseException::ERR_BAD_PARAMETERS, 'username');
        }

        if (false === isset($config['password'])) {

            throw new DatabaseException(DatabaseException::ERR_BAD_PARAMETERS, 'password');
        }

        if (false === isset($config['dbname'])) {

            throw new DatabaseException(DatabaseException::ERR_BAD_PARAMETERS, 'dbname');
        }
    }

}