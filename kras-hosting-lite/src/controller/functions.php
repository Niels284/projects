<?php

namespace Controllers {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    if (
        $_SESSION['current_page'] !== 'edit' &&
        $_SESSION['current_page'] !== 'admin'
    ) {
        require_once '../../../model/database.php';
    } else {
        require_once '../../../../model/database.php';
    }

    use Database\CRUD;
    use PDOException;

    class Functions extends Calculations
    {
        protected string $dsn;
        protected string $username;
        protected string $password;

        protected object $db; // default empty

        public function __construct()
        {
            $this->dsn = ""; // removed for security reasons
            $this->username = ""; // removed for security reasons
            $this->password = ""; // removed for security reasons
        }

        // function to get data of a table from database by type
        protected function getTable(string $type)
        {
            $this->db = new CRUD($this->dsn, $this->username, $this->password, $type);
        }

        // function to get data of a table from database
        public function get(string $type, $select, $condition)
        {
            try {
                $this->getTable($type);
                if ($this->db) {
                    return $this->db->read($select, $condition);
                } else {
                    echo "Kan geen verbinding maken met de database.";
                }
            } catch (PDOException $e) {
                echo "Databasefout: " . $e->getMessage();
            }
        }

        public function sign_in($username, $password)
        {
            if (empty($username) || empty($password)) {
                return ['error' => 'Vul alle velden in'];
            } else {
                $this->getTable('customers');
                $user = $this->db->read('*', "WHERE name = '$username' AND password = '$password'");
                if (!empty($user)) {
                    $_SESSION['user']['id'] = $user[0]->customerid;
                    return ['succes' => 'Ingelogd'];
                } else {
                    return ['error' => 'Gebruikersnaam of wachtwoord is ongeldig'];
                }
            }
        }

        public function sign_out()
        {
            unset($_SESSION['user']);
            return ['success' => 'U bent succesvol uitgelogd'];
        }

        public function update(string $type, array $updateData, $id)
        {
            try {
                $this->getTable($type);
                if ($this->db) {
                    $this->db->update($type, $updateData, $id);
                } else {
                    echo "Kan geen verbinding maken met de database.";
                }
            } catch (PDOException $e) {
                echo "Databasefout: " . $e->getMessage();
            }
        }
    }
    class Calculations
    {
        public function getCurrentTime($format)
        {
            $timezone = new \DateTimeZone('Europe/Amsterdam');
            $date = new \DateTime('now', $timezone);
            return $format == 'date'
                ? $date->format('d-m-Y')
                : $date->format('H:i:s');
        }

        // function returns string like '../../' to make a path from current file to target file
        public static function getPath($absolute_current_path, $target)
        {
            $extraPath =
                $_SESSION['current_page'] !== 'edit' && $_SESSION['current_page'] !== 'admin'
                ? '' : '../';

            // Bereken het aantal "../" om van $absolute_current_path naar $absolute_target_path te gaan
            $relative_path = '';

            while (
                strpos($absolute_current_path, $target) !== false
            ) {
                $relative_path .= '../';
                $absolute_current_path = dirname($absolute_current_path);
            }
            return $relative_path .= $extraPath;
        }
    }
}
