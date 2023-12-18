<?php

use Controllers\Functions;
use Controllers\Calculations;

ob_start(); // Schakel outputbuffering in

ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$path = Calculations::getPath(__DIR__, 'view');

if (isset($_GET['call']) && $_GET['call'] === 'sign_out') {
    $logout = (new Functions)->sign_out();
    $_SESSION['success_message'] = $logout['success'];
    if ($_SESSION['current_page'] === 'admin') {
        header('Location: ../../login');
    } else {
        header('Location: ../login');
    }
}
?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $_SESSION['current_page'] ?></title>
    <link href="<?php echo $path ?>style/main.css" rel="stylesheet">
    <link href="<?php echo $path . 'style/' . $_SESSION['current_page'] . '.css' ?>" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Average+Sans&family=Fira+Sans+Condensed:wght@400;700&display=swap" rel="stylesheet">
</head>

<body>
    <header>
        <div class="headerInner">
            <div class="logoContainer">
                <img src="" alt="LOGO">
            </div>
            <div class="navContainer">
                <a href="<?php echo $path ?>pages/home">HOME</a>
                <a href="<?php echo $path ?>pages/home">ABOUT</a>
                <a href="<?php echo $path ?>pages/home">PRODUCTS</a>
                <a href="<?php echo $path ?>pages/home">CONTACT</a>
                <?php
                if (isset($_SESSION['user']) && array_key_exists('id', $_SESSION['user'])) {
                    echo '<a href="' . $path . 'pages/login">ADMIN</a>';
                    echo '<a href="?call=sign_out">UITLOGGEN</a>';
                } else {
                    echo '<a href="' . $path . 'pages/login">LOGIN</a>';
                }
                ?>
            </div>
        </div>
    </header>