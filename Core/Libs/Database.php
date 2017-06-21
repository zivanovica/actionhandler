<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/14/17
 * Time: 10:59 PM
 */

namespace Core\Libs;

use Core\CoreUtils\Singleton;
use Core\Exceptions\DatabaseException;

class Database
{

    use Singleton;

    /** @var \PDO */
    public $connection;

    public function __construct(string $host, string $database, string $username, string $password, int $port = 3306)
    {

        $dsn = "mysql:host={$host};port={$port};dbname={$database};";

        $this->connection = new \PDO($dsn, $username, $password);

    }

    /**
     *
     * Fetch single database record
     *
     * @param string $query
     * @param array $bindings
     * @return array
     * @throws DatabaseException
     */
    public function fetch(string $query, array $bindings = []): ?array
    {

        $statement = $this->_executeQuery($query, $bindings);

        $results = $statement->fetch(\PDO::FETCH_ASSOC);

        return is_array($results) ? $results : null;
    }


    public function store(string $query, array $bindings): int
    {

        $this->_executeQuery($query, $bindings);

        return $this->connection->lastInsertId();
    }

    public function delete(string $query, array $bindings): int
    {

        $statement = $this->_executeQuery($query, $bindings);

        return $statement->rowCount();
    }

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