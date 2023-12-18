<?php

namespace Controller {

    error_reporting(E_ALL);
    ini_set('display_errors', '1');
    ini_set('display_startup_errors', 1);

    require_once "database.php";

    use DatabaseUpdated;
    use PDOException;

    class Database
    {
        private \Medoo\Medoo $db;

        public function __construct()
        {
            $this->db = DatabaseUpdated::getInstance();
        }

        public function createUser(
            string $firstname,
            string $lastname,
            string $emailaddress,
            string $username,
            string $salt,
            string $hashedPassword
        ): array // aanmaken van gebruiker
        {
            try {
                $klantenData = [
                    'VOORNAAM' => $firstname,
                    'ACHTERNAAM' => $lastname,
                    'EMAILADRES' => $emailaddress,
                ];
                $this->db->insert('klanten', $klantenData);
                $klantenID = $this->db->id(); // ophalen van de laatst toegevoegde ID

                $gebruikersData = [
                    'GEBRUIKERSNAAM' => $username,
                    'SALTING' => $salt,
                    'HASH_WACHTWOORD' => $hashedPassword,
                    'AANTAL_LOGINS' => 0,
                    'LAATSTE_LOGIN' => null,
                    'BLOCKED' => 0,
                ];
                $this->db->insert('accounts', $gebruikersData);
                $accountID = $this->db->id(); // ophalen van de laatst toegevoegde ID

                // ID's opslaan in 'gebruikers' tabel
                $this->db->insert('gebruikers', ['ID_PERSON' => $klantenID, 'ID_ACCOUNT' => $accountID]);
                return ['success' => 'Account is aangemaakt!'];
            } catch (PDOException $e) {
                return ['error' => $e];
            }
        }

        public function checkLogin(string $username, string $password): array // controleren van login
        {
            $user = $this->getUser($username);
            if (isset($user['error'])) {
                return $user;
            } else if ($user['BLOCKED'] === 1) {
                return ['error' => "Uw account is definitief geblokkeerd! Vraag een medewerker om hulp!"];
            }
            if (password_verify($password . $user['SALTING'], $user['HASH_WACHTWOORD'])) { // wachtwoord controleren, komt het overeen met de hash?
                if (!array_key_exists('user', $_SESSION)) {
                    $_SESSION['user'] = [
                        'id' => $user['ID'],
                        'username' => $user['GEBRUIKERSNAAM'],
                    ];
                }
                $this->successLogin($user['ID'], $user['GEBRUIKERSNAAM'], $user['AANTAL_LOGINS']);
                return ['success' => "U bent ingelogd! U wordt nu doorgestuurd naar de homepagina!"];
            } else {
                if (!array_key_exists('failedLogin', $_SESSION)) { // failed login registreren in SESSION, > 3? Gebruiker blokkeren
                    $_SESSION['failedLogin'] = 1;
                } else if ($_SESSION['failedLogin'] == 3) {
                    $this->failedLogin($user['ID'], $user['GEBRUIKERSNAAM']);
                    unset($_SESSION['failedLogin']);
                    return ['error' => "Uw account is definitief geblokkeerd! Vraag een medewerker om hulp!"];
                } else {
                    $_SESSION['failedLogin']++;
                }
                return ['error' => "Ongeldige gebruikersnaam of wachtwoord!"];
            }
        }

        protected function getUser(string $username) // controleren of de gebruiker bestaat, zo ja? De gebruikergegevens ophalen
        {
            try {
                $result = $this->db->select(
                    'accounts',
                    ['ID', 'GEBRUIKERSNAAM', 'SALTING', 'HASH_WACHTWOORD', 'AANTAL_LOGINS', 'BLOCKED'],
                    ['GEBRUIKERSNAAM' => $username]
                );
                if (!empty($result) > 0) {
                    return $result[0];
                } else {
                    return ['error' => "Gebruiker bestaat niet!"];
                }
            } catch (PDOException) {
                return ['error' => "Er is iets misgegaan, probeer het opnieuw!"];
            }
        }

        protected function successLogin(string $id, string $username, $aantalLogins) // registreren van login (updaten naar meest recente login datum)
        {
            if ($aantalLogins === null) {
                $logins = 1;
            } else {
                $logins = $aantalLogins + 1;
            }
            try {
                $this->db->update(
                    'accounts',
                    ['AANTAL_LOGINS' => $logins, 'LAATSTE_LOGIN' => date('Y-m-d H:i:s')],
                    ['ID' => $id, 'GEBRUIKERSNAAM' => $username]
                );
            } catch (PDOException) {
                return ['error' => "Er is iets misgegaan, probeer het opnieuw!"];
            }
        }

        protected function failedLogin(string $id, string $username) // blokkeren van gebruiker
        {
            try {
                $this->db->update(
                    'accounts',
                    ['BLOCKED' => 1],
                    ['ID' => $id, 'GEBRUIKERSNAAM' => $username]
                );
            } catch (PDOException) {
                return ['error' => "Er is iets misgegaan, probeer het opnieuw!"];
            }
        }
    }
}
