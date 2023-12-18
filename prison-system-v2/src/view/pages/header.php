<?php
require_once $_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Controller\Functions;

if (($_SESSION['current_page'] !== "login") && (!array_key_exists('user', $_SESSION))) {
    header('location: ../login');
}

if (isset($_GET['call']) && $_GET['call'] === 'sign_out') {
    (new Functions)->sign_out();
}

function getPath($absolute_current_path, $target)
{
    $extraPath = $_SESSION['current_page'] !== 'edit' ? '' : '../';

    $relative_path = '';

    while (
        strpos($absolute_current_path, $target) !== false
    ) {
        $relative_path .= '../';
        $absolute_current_path = dirname($absolute_current_path);
    }
    return $relative_path .= $extraPath;
}

$quickPath = getPath(__DIR__, 'src');

$_SESSION['current_page_dir'] = $quickPath;
$path = getPath(__DIR__, 'view');

?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="<?php echo $path ?>style/cssreset.css">
    <link rel="stylesheet" href="<?php echo $path ?>style/scss/build/main.css">
    <link rel="stylesheet" href="<?php echo $path ?>style/scss/build/<?php echo $_SESSION['current_page'] ?>.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@latest/font/bootstrap-icons.css" />
    <link href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet" />
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.4/jquery.min.js"></script>
    <script type="module" src="<?php echo $path ?>js/main.js"></script>
    <title>project - prison system</title>
</head>

<body>
    <?php
    if (
        array_key_exists('current_page', $_SESSION)
        && ($_SESSION['current_page'] !== 'login')
        && ($_SESSION['current_page'] !== 'error')
    ) {
        echo '
        <nav class="navbar navbar-expand-lg bg-light">
        <div class="container-fluid">
            <a class="navbar-brand" href="../dashboard">Prison System - v1.0</a>
            <div class="d-flex justify-content-end collapse navbar-collapse" id="navbarNavAltMarkup">
                <div class="navbar-nav">
                    <a class="nav-link mx-3" aria-current="page" href="../dashboard">Dashboard</a>
                    <a class="nav-link mx-3" href="../personal_settings">Persoonlijke gegevens</a>
                    <a class="nav-link mx-3" href="?call=sign_out">Uitloggen</a>
                </div>
            </div>
        </div>
    </nav>
        ';
    }
    ?>