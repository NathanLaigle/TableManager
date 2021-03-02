<?php

// Define DB connexion
define('DB_CONNECT', [
    "user_name" => "root",
    "user_password" => "root",
    "host" => "localhost",
    "db_name" => "mail",
]);

class TableManager
{

    // DB table name
    protected $table;

    // Pdo connexion
    protected $pdo;

    // Constructor
    public function __construct(string $table)
    {
        $this->table = $table;
    }

    // Db connexion
    public function db_connect()
    {
        try {
            // Get db connexion
            $this->pdo = new PDO(
                'mysql:host='. DB_CONNECT['host'] .';dbname='. DB_CONNECT['db_name'] .'',
                ''. DB_CONNECT['user_name'] .'',
                ''. DB_CONNECT['user_password'] .''
            );
            // Get Fetch mode
            $this->pdo->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_OBJ);
        } catch (PDOException $e) {
            echo $e;
            die();
        }
    }

    // Start CRUD actions

    // READ (SELECT)
    // Select all row
    public function findAll()
    {
        try{
            $sql = "SELECT * FROM {$this->table}";
            $request = $this->pdo->prepare($sql);
            $request->execute();
            $result = $request->fetchAll();
            return $result;
        } catch (PDOException $e){
            echo $e;
            die();
        }
    }

    // Select row where ...
    public function findBy(array $condition)
    {
        // Create condition
        $i = 0;
        $conditions = "";
        foreach ($condition as $attribut => $value) {
            if ($i != count($condition) - 1) {
                $conditions .= $attribut. " = ". $this->pdo->quote($value). " AND ";
            } else {
                $conditions .= $attribut. " = ". $this->pdo->quote($value);
            }
            $i++;
        }

        // Execute
        try {
            $sql = "SELECT * FROM {$this->table} WHERE $conditions";
            $request = $this->pdo->prepare($sql);
            $request->execute();
            $result = $request->fetchAll();
            return $result;
        } catch (PDOException $e) {
            echo $e;
            die();
        }
    }

    // Select one row where ...
    // this function only accepte a unique key=>value condition
    public function findOneBy(array $condition)
    {
        // Create condition
        $i = 0;
        $conditions = "";
        foreach ($condition as $attribut => $value) {
            if ($i != count($condition) - 1) {
                $conditions .= $attribut. " = ". $this->pdo->quote($value). " AND ";
            } else {
                $conditions .= $attribut. " = ". $this->pdo->quote($value);
            }
            $i++;
        }

        // Execute
        try {
            $sql = "SELECT * FROM {$this->table} WHERE $conditions";
            $request = $this->pdo->prepare($sql);
            $request->execute();
            $result = $request->fetch();
            return $result;
        } catch (PDOException $e) {
            echo $e;
            die();
        }
    }

    // CREATE (INSERT)
    // Do not use assoc tabs /!\
    public function insert(array $data)
    {
        // Get values to add
        $values = "(";
        for ($i = 0; $i < count($data); $i++) {
            // Do not quote "null" values
            if ($data[$i] != null) {
                $data[$i] = $this->pdo->quote($data[$i]);
            } elseif ($data[$i] == null) {
                $data[$i] = "null";
            }

            // Do not add a comma to the last value
            if ($i != count($data) - 1) {
                $values .= $data[$i].", ";
            } else {
                $values .= $data[$i];
            }
        }
        $values .= ")";

        // set sql request
        $sql = "INSERT INTO {$this->table} VALUES $values";
        $request = $this->pdo->prepare($sql);
        $request->execute();
    }

    // DELETE
    public function delete(array $condition)
    {
        // Create condition
        $i = 0;
        $conditions = "";
        foreach ($condition as $attribut => $value) {
            if ($i != count($condition) - 1) {
                $conditions .= $attribut. " = ". $this->pdo->quote($value). " AND ";
            } else {
                $conditions .= $attribut. " = ". $this->pdo->quote($value);
            }
            $i++;
        }

        // Execute
        try {
            $sql = "DELETE FROM {$this->table} WHERE $conditions";
            $request = $this->pdo->prepare($sql);
            $request->execute([
                            "val" => $value
                        ]);
        } catch (PDOException $e) {
            echo $e;
            die();
        }
    }

    // UPDATE
    public function update(array $condition, array $data)
    {
        // Create conditions
        $i = 0;
        $conditions = "";
        foreach ($condition as $attribut => $value) {
            if ($i != count($condition) - 1) {
                $conditions .= $attribut. " = ". $this->pdo->quote($value). " AND ";
            } else {
                $conditions .= $attribut. " = ". $this->pdo->quote($value);
            }
            $i++;
        }
        // Create data to updtade
        $i = 0;
        $datas = "";
        foreach ($data as $attribut => $value) {
            if ($i != count($data) -1) {
                $datas .= $attribut. " = ". $this->pdo->quote($value). ", ";
            } else {
                $datas .= $attribut. " = ". $this->pdo->quote($value);
            }
            $i++;
        }
        // Execute
        try {
            $sql = "UPDATE {$this->table} SET $datas WHERE $conditions";
            $request = $this->pdo->prepare($sql);
            $request->execute();
        } catch (PDOException $e) {
            echo $e;
            die();
        }
    }
}