<?php
session_start();

require_once $_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php";
include_once "../../../controller/functions.php";

use Controller\Database;

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', 1);

$database = new Database();

if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
} else {
    $message;
}
$usernameError;
$passwordError;

if (isset($_POST['login_account']) && $_POST['randcheck'] == $_SESSION['rand']) {
    if (isset($_POST['username']) && isset($_POST['password'])) {
        $username = is_valid_username(trim($_POST['username']));
        $password = is_valid_password(trim($_POST['password']));
        $_POST = array();
        if (!isset($message) && !isset($usernameError) && !isset($passwordError)) {
            $message = $database->checkLogin($username, $password);
            if (!array_key_exists('error', $message)) {
                header('Location:../gebruikers_menu');
            }
        }
    } else {
        $message = ['error' => "Vul alle velden in!"];
    }
}

$rand = rand();
$_SESSION['rand'] = $rand; // prevent resubmitting form

?>
<!DOCTYPE html>
<html lang="nl">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.7.1/jquery.min.js"></script>
    <link rel="stylesheet" href="../../css/cssreset.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="../../css/style.css">
    <title>Hasing - Salting</title>
</head>

<body>
    <section class="vh-100 bg-image" style="background-image: url('https://mdbcdn.b-cdn.net/img/Photos/new-templates/search-box/img4.webp');">
        <div class="mask d-flex align-items-center h-100 gradient-custom-3">
            <div class="container h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                        <div class="card" style="border-radius: 15px;">
                            <div class="card-body p-5">
                                <h2 class="text-uppercase text-center mb-5">Inloggen</h2>
                                <form method="POST">
                                    <div class="form-outline mb-4">
                                        <input type="text" id="username" name="username" class="form-control form-control-lg" />
                                        <label class="form-label" for="username">Gebruikersnaam</label>
                                    </div>
                                    <?php
                                    if (!empty($usernameError)) {
                                        echo "<div class='alert alert-danger' role='alert'>" . $usernameError['error'] . "</div>";
                                        $usernameError = "";
                                    }
                                    ?>
                                    <div class="form-outline mb-4">
                                        <input type="password" id="password" name="password" class="form-control form-control-lg" />
                                        <label class="form-label" for="password">Wachtwoord</label>
                                    </div>
                                    <?php
                                    if (!empty($passwordError)) {
                                        echo "<div class='alert alert-danger' role='alert'>" . $passwordError['error'] . "</div>";
                                        $passwordError = "";
                                    }
                                    ?>
                                    <div class=" d-flex justify-content-center" style="margin-bottom:20px">
                                        <input type="hidden" name="randcheck" value="<?php echo $rand; ?>" />
                                        <button type="submit" class="btn btn-success btn-block btn-lg gradient-custom-4 text-body" name="login_account">Aanmelden</button>
                                    </div>
                                    <?php
                                    if (!empty($message)) {
                                        if (!empty($message['error'])) {
                                            echo "<div class='alert alert-danger' role='alert'>" . $message['error'] . "</div>";
                                        } else if (!empty($message['success'])) {
                                            echo "<div class='alert alert-success' role='alert'>" . $message['success'] . "!</div>";
                                        } else if (!empty($message)) {
                                            echo "<div class='alert alert-success' role='alert'>" . $message . "</div>";
                                        }
                                        $message = "";
                                    }
                                    ?>
                                    <a href="../register" style="text-align: center">Ik heb heb nog geen account</a>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</body>
<script type="module" src="../../js/script.js"></script>

</html>