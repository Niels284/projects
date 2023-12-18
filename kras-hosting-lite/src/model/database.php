<?php

namespace Database {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    use PDO;
    use PDOException;
    use Exception;

    class Database
    {
        private string $dsn;
        private string $username;
        private string $password;

        private string $errorMessage;
        private object $db;

        public function __construct(
            string $dsn,
            string $username,
            string $password,
        ) {
            $this->dsn = $dsn;
            $this->username = $username;
            $this->password = $password;
        }

        protected function connect(): PDO
        {
            try {
                $this->db = new PDO($this->dsn, $this->username, $this->password);
                $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                return $this->db;
            } catch (PDOException $e) {
                $this->errorMessage = 'Database Error: ';
                $this->errorMessage .= $e->getMessage();
                exit();
            }
        }
    }

    class CRUD extends Database
    {
        private object $db;
        private string $type;

        public function __construct(
            string $dsn,
            string $username,
            string $password,
            string $type
        ) {
            $db = new Database($dsn, $username, $password);
            try {
                $this->db = $db->connect();
                $this->type = $type;
            } catch (PDOException $e) {
                echo 'Databasefout: ' . $e->getMessage();
            }
        }

        protected function create()
        {
        }

        public function read($select, $condition)
        {
            $condition == null
                ? $sql = 'SELECT ' . $select . ' FROM ' . $this->type
                : $sql = 'SELECT ' . $select . ' FROM ' . $this->type . ' ' . $condition;

            $query = $this->db->prepare($sql);
            try {
                $query->execute();
                $results = $query->fetchAll(PDO::FETCH_OBJ);
                if ($query->rowCount() > 0) {
                    return $results;
                } else {
                    return;
                }
            } catch (PDOException $e) {
                throw new Exception('Databasefout: ' . $e->getMessage());
            }
        }

        public function update($type, array $updateData, $id)
        {
            $sql = 'UPDATE ' . $this->type . ' SET ';
            $updateColumns = array_keys($updateData);
            $updateValues = array_values($updateData);

            foreach ($updateColumns as $column) {
                $sql .= $column . ' = ?, ';
            }
            $sql = rtrim($sql, ', ');
            $sql .= 'WHERE ' . substr_replace($type, "", -1) . 'id= ?';

            $query = $this->db->prepare($sql);

            try {
                $updateValues[] = $id;

                $query->execute($updateValues);
                if ($query->rowCount() > 0) {
                    return true;
                } else {
                    throw new Exception('No results found.');
                }
            } catch (PDOException $e) {
                throw new Exception('Database error: ' . $e->getMessage());
            }
        }

        protected function delete()
        {
        }
    }
}
