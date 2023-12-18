<?php

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', 1);

// INPUT VALIDATION FUNCTIONS

function is_valid_name($name) // controleren van voor- en achternaam
{
    global $nameError;
    if (preg_match('/^[a-zA-Z\-\' ]+$/', $name)) {
        return htmlspecialchars($name);
    } else {
        $nameError = [
            'error' => "Naam mag alleen letters, spaties, koppeltekens en apostrofen bevatten!"
        ];
    }
}

function is_valid_email($email) // controleren van e-mailadres
{
    global $emailError;
    if (preg_match('/^[a-zA-Z0-9._-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/', $email)) {
        return htmlspecialchars($email);
    } else {
        $emailError = [
            'error' => "Ongeldig e-mailadres!"
        ];
    }
}

function is_valid_username($username) // controleren van gebruikersnaam
{
    global $usernameError;
    if (preg_match('/^[a-zA-Z0-9]+$/', $username)) {
        return htmlspecialchars($username);
    } else {
        $usernameError = [
            'error' => "Gebruikersnaam mag alleen letters en cijfers bevatten!"
        ];
    }
}

function is_valid_password($password) // controleren van wachtwoord
{
    global $passwordError;
    // Minstens 8 tekens lang, met minstens één hoofdletter, één kleine letter en één cijfer
    if (preg_match('/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d).{8,}$/', $password)) {
        return htmlspecialchars($password);
    } else {
        $passwordError = [
            'error' => "Wachtwoord moet minstens 8 tekens lang zijn, met minstens één hoofdletter, één kleine letter en één cijfer!"
        ];
    }
}

function is_valid_code($code) // controleren van 6-cijferige code
{
    global $codeError;
    if (preg_match('/^[0-9]{6}$/', $code)) {
        return htmlspecialchars($code);
    } else {
        $codeError = [
            'error' => "Code moet 6 cijfers lang zijn!"
        ];
    }
}
