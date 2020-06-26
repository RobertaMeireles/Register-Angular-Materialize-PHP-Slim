<?php namespace Core\Database;

use PDO;
use PDOException;
use PDOStatement;
use stdClass;

class Database {
    
    private $host;
    private $user;
    private $pass;
    private $name;
    private $port;

    private $connection;

    public function __construct(string $host = null, string $user = null, string $pass = null, string $name = null, int $port = 3306) {
        $this->host = $host;
        $this->user = $user;
        $this->pass = $pass;
        $this->name = $name;
        $this->port = $port;

        $this->connect();
    }

    private function connect(): void {
        $dsn = sprintf("mysql:host=%s;dbname=%s;port=%d", $this->host, $this->name, $this->port);

        try {
            $options = [
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ
            ];

            $this->connection = new PDO($dsn, $this->user, $this->pass, $options);
        } catch (PDOException $e) {
            die("Error connecting to database: " .  $e->getMessage());
        }
    }

    //Retorna a conexao, retorna todo o PDO
    public function getConnection(): PDO {
        return $this->connection;
    }

    //PERMITE EXECULTAR QUALQUER SQL, QUANDO QUER UM SQL MAIS CUSTOMIZADO UTILIZE ESSE METODO 
    public function query(string $sql, array $data = []): PDOStatement {
        $stmt = $this->connection->prepare($sql);
        $stmt->execute($data);

        return $stmt;
    }

    //RETORNA TUDO DE UMA TABELA SQL
    public function all(string $table): array {
        $stmt = $this->query("SELECT * FROM $table");
        return $stmt->fetchAll();
    }

    //RETORNA TUDO DE UMA TABELA SQL MAS COM UM PARAMETRO WHERE
    public function where(string $table, string $where, array $data = []): array {
        $stmt = $this->query("SELECT * FROM $table WHERE $where", $data);
        return $stmt->fetchAll();
    }

    //RETORNA UM ITEM DA TABELA COM UM CRITERIO ID
    public function byId(string $table, $id) {
        $stmt = $this->query("SELECT * FROM $table WHERE id = :id LIMIT 1", ['id' => $id]);
        $result = $stmt->fetch();
        return $result;
    }

    //INSERE UM ITEM DE ALGUMA TABELA
    public function insert(string $table, array $data): stdClass {
        $fields = array_keys($data);
        $fieldsAsString = implode(', ', $fields);
        $valuesAsString = ':' . implode(', :', $fields);

        $sql = "INSERT INTO $table ($fieldsAsString) VALUES ($valuesAsString)";
        $stmt = $this->query($sql, $data);

        $result = new stdClass();
        $result->stmt = $stmt;
        $result->lastInsertId = $this->connection->lastInsertId();

        return $result;
    }

    //FAZ O UPDATE DE UM ITEM DE ALGUMA TABELA
    public function update(string $table, array $data, string $where, array $whereData = []): PDOStatement {
        $pairs = array_map(function($key) {
            return "$key = :$key";
        }, array_keys($data));

        $pairsAsString = implode(', ', $pairs);
        $sql = "UPDATE $table SET $pairsAsString WHERE $where";
        $stmt = $this->query($sql, array_merge($data, $whereData));

        return $stmt;
    }

    //DELETA UM ITEM DA TABELA
    public function delete(string $table, string $where = null, array $data = []): PDOStatement {
        $sql = "DELETE FROM $table WHERE ";
        
        if (is_null($where)) {
            $keys = [];
            foreach ($data as $key => $value) {
                $keys[] = "$key = :$key";
            }
            $sql .= implode(' AND ', $keys);
        } else {
            $sql .= $where;
        }

        $stmt = $this->query($sql, $data);
        return $stmt;
    }

    //FAZ UM EXISTS 
    public function exists(string $table, string $field, string $value): bool {
        $sql = "SELECT COUNT(*) AS counter FROM $table WHERE $field = :value LIMIT 1";
        $stmt = $this->query($sql, ['value' => $value]);

        $result = $stmt->fetch();
        return $result->counter > 0;
    }
}