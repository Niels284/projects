<?php

namespace Controller;

use DatabaseUpdated;

class Functions extends Calculations
{
    private \Medoo\Medoo $db;

    public function __construct()
    {
        $this->db = DatabaseUpdated::getInstance();
    }

    // standaard functies

    public function sign_in(?string $username, ?string $password): array // inloggen van gebruiker
    {
        if (empty($username) || empty($password)) {
            return ['error' => 'Vul alle velden in'];
        }
        if (!$this->db->has('users', ['username' => $username])) {
            return ['error' => 'Ongeldige gebruikersnaam/wachtwoord'];
        }
        $user = $this->db->get(
            'users',
            [
                '[>]staff_details' => ['id_service_number' => 'id_service_number']
            ],
            [
                'users.id_user',
                'users.id_service_number',
                'users.password',
            ],
            [
                'users.username' => $username
            ]
        );
        if (!password_verify($password, $user['password'])) {
            return ['error' => 'Ongeldige gebruikersnaam/wachtwoord'];
        }
        $_SESSION['user'] = array(
            'id' => $user['id_user'],
            'service_number' => $user['id_service_number'],
        );
        return ['success' => 'Ingelogd'];
    }

    public function sign_out(): void // uitloggen van gebruiker
    {
        if (array_key_exists('user', $_SESSION)) {
            unset($_SESSION['user']);
        }
        $_SESSION['success_message'] = 'U bent succesvol uitgelogd';
        header('location: ../login');
    }

    public function verify_session(): void // sessie van gebruiker controleren
    {
        if (isset($_SESSION['id'])) {
            return;
        }
        header('Location: ../login');
    }

    public function delete_account(?string $id_user): array // account verwijderen
    {
        if (empty($id_user) || !$this->db->has('users', ['id_user' => $id_user])) {
            return ['error' => 'Er is een fout opgetreden'];
        }
        $this->db->delete('users', ['id_user' => $id_user]);
        return ['success' => 'Account is succesvol verwijderd'];
    }

    public function get_account_info(?string $id_user): array // account info ophalen
    {
        if (empty($id_user) || !$this->db->has('users', ['id_user' => $id_user])) {
            return ['error' => 'Er is een fout opgetreden'];
        }
        return $this->db->get(
            'users',
            [
                '[>]staff_details' => ['id_service_number' => 'id_service_number']
            ],
            [
                'users.id_user',
                'users.id_service_number',
                'users.username',
                'users.password',
                'staff_details.firstname',
                'staff_details.lastname',
                'staff_details.emailaddress',
                'staff_details.phone_number',
                'staff_details.address',
                'staff_details.function',
                'staff_details.supervisor'
            ],
            [
                'users.id_user' => $id_user
            ]
        );
    }

    // account settings

    public function update_username(?string $current_username, ?string $new_username): array // gebruikersnaam updaten
    {
        if (empty($current_username) || empty($new_username)) {
            return ['error' => 'Vul alle velden in'];
        }
        if (!$this->db->has('users', ['username' => $current_username])) {
            return ['error' => 'Ongeldige gebruikersnaam'];
        }
        $this->db->update('users', ['username' => $new_username], ['username' => $current_username]);
        return ['success' => 'Gebruikersnaam is succesvol gewijzigd'];
    }

    public function update_password(?string $current_password, ?string $new_password, ?string $id_user): array // wachtwoord updaten
    {
        if (empty($current_password) || empty($new_password)) {
            return ['error' => 'Vul alle velden in'];
        }
        if (!password_verify($current_password, $this->db->get('users', 'password', ['id_user' => $id_user]))) {
            return ['error' => 'Ongeldig wachtwoord'];
        }
        $this->db->update('users', ['password' => $new_password], ['id_user' => $id_user]);
        return ['success' => 'Wachtwoord is succesvol gewijzigd'];
    }

    // gebruikers management functies

    public function update_supervisor_permissions(?string $value, $id_service_number): void // supervisor permissies updaten
    {
        $this->db->update('staff_details', ['supervisor' => $value], ['id_service_number' => $id_service_number]);
    }

    public function update_user_settings(
        ?array $updated_user_settings
    ): array // Alle gewijzigde user settings updaten
    {
        $userFields = [
            'username' => $updated_user_settings["username"],
            'password' => $updated_user_settings["password"]
        ];

        $staffFields = [
            'firstname' => $updated_user_settings["firstname"],
            'lastname' => $updated_user_settings["lastname"],
            'emailaddress' => $updated_user_settings["emailaddress"],
            'phone_number' => $updated_user_settings["phone_number"],
            'address' => $updated_user_settings["address"],
            'function' => $updated_user_settings["function"],
        ];

        $userFields = array_filter($userFields, function ($value) {
            return !empty($value);
        });

        $staffFields = array_filter($staffFields, function ($value) {
            return !empty($value);
        });

        $staffFields['supervisor'] = $updated_user_settings["supervisor"];

        if (empty($updated_user_settings["service_number"])) {
            return ['error' => 'Vul personeelsnummer in'];
        }

        if (empty($userFields) && empty($staffFields)) {
            return ['error' => 'Geen velden om bij te werken'];
        }

        if (!$this->db->has('users', ['id_service_number' => $updated_user_settings["service_number"]])) {
            return ['error' => 'Ongeldig personeelsnummer'];
        }

        if (!empty($userFields)) {
            $this->db->update(
                'users',
                $userFields,
                [
                    'id_service_number' => $updated_user_settings["service_number"]
                ]
            );
        }

        if (!empty($staffFields)) {
            $this->db->update(
                'staff_details',
                $staffFields,
                [
                    'id_service_number' => $updated_user_settings["service_number"]
                ]
            );
        }

        return ['success' => 'Gebruikersinstellingen zijn succesvol gewijzigd'];
    }

    public function add_new_user(
        ?array $add_new_user
    ): array // nieuwe gebruiker toevoegen aan tabel 'users' en 'staff_details' en een relatie leggen tussen de twee
    {
        $userFields = [
            'username' => $add_new_user["username"],
            'password' => $add_new_user["password"]
        ];

        $staffFields = [
            'firstname' => $add_new_user["firstname"],
            'lastname' => $add_new_user["lastname"],
            'emailaddress' => $add_new_user["emailaddress"],
            'phone_number' => $add_new_user["phone_number"],
            'address' => $add_new_user["address"],
            'function' => $add_new_user["function"],
            'supervisor' => $add_new_user["supervisor"]
        ];

        $userFields = array_filter($userFields, function ($value) {
            return !empty($value);
        });

        $staffFields = array_filter($staffFields, function ($value) {
            if ($value == 0) {
                return $value;
            } else {
                return !empty($value);
            }
        });

        if (empty($userFields) && empty($staffFields)) {
            return ['error' => 'Geen velden om bij te werken'];
        }

        if (!empty($userFields)) {
            $this->db->insert(
                'users',
                $userFields
            );

            // Haalt de gegenereerde id_user op (deze moet worden opgehaald uit de "users" tabel)
            $idUser = $this->db->id();
        }

        if (!empty($staffFields) && isset($idUser)) {
            $staffFields['id_user'] = $idUser; // Voegt de gegenereerde id_user toe aan de $staffFields array
            $this->db->insert(
                'staff_details',
                $staffFields
            );

            // Haal de gegenereerde id_service_number (deze moet worden opgehaald uit de "staff_details" tabel)
            $idServiceNumber = $this->db->id();

            // Koppelt de gebruiker aan de hand van de id_user aan de "users" tabel
            $this->db->update(
                'users',
                ['id_service_number' => $idServiceNumber],
                [
                    'id_user' => $idUser
                ]
            );
        }

        return ['success' => 'Gebruikersinstellingen zijn succesvol gewijzigd'];
    }

    public function get_users(): array // gebruikers ophalen voor user_management pagina
    {
        return $this->db->select(
            'staff_details',
            [
                '[>]users' => ['id_service_number' => 'id_service_number']
            ],
            [
                'users.id_user',
                'users.id_service_number',
                'users.username',
                'users.password',
                'staff_details.firstname',
                'staff_details.lastname',
                'staff_details.emailaddress',
                'staff_details.phone_number',
                'staff_details.address',
                'staff_details.function',
                'staff_details.supervisor'
            ]
        );
    }
}

class Calculations
{
    // function returns string like '../../' to make a path from current file to target file
    public static function getPath($absolute_current_path, $target): string
    {
        $extraPath = $_SESSION['current_page'] !== 'edit' ? '' : '../';

        // Bereken het aantal "../" om van $absolute_current_path naar $absolute_target_path te gaan
        $relative_path = '';

        while (
            strpos($absolute_current_path, $target) !== false
        ) {
            $relative_path .= '../ ';
            $absolute_current_path = dirname($absolute_current_path);
        }
        return $relative_path .= $extraPath;
    }
}
