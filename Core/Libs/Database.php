<?php
/**
 * Created by IntelliJ IDEA.
 * User: coa
 * Date: 6/14/17
 * Time: 10:59 PM
 */

namespace Core\Libs;

use Core\CoreUtils\Singleton;

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

    public function fetch(string $query, array $bindings = []): array
    {

    }

}