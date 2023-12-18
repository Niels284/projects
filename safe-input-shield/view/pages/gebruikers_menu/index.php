<?php
session_start();

require_once $_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php";

use Controller\Database;

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', 1);

$database = new Database();

if (
    !array_key_exists('user', $_SESSION) ||
    !array_key_exists('id', $_SESSION['user']) ||
    !isset($_SESSION['user']['id'])
) {
    header("Location: ../login");
}

if (isset($_GET['call']) && $_GET['call'] === 'log_out') {
    session_destroy();
    header("Location: ../login");
}

$message;

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
                                <h2 class="text-uppercase text-center mb-5">Gebruikersgegevens</h2>
                                <form>
                                    <div class="form-outline mb-4">
                                        <input type="text" id="username" class="form-control form-control-lg" value="<?php echo $_SESSION['user']['username'] ?>" disabled />
                                        <label class="form-label" for="username">Uw gebruikersnaam</label>
                                    </div>
                                    <div class=" d-flex justify-content-center" style="margin-bottom:20px">
                                        <a href="?call=log_out" class="btn btn-success btn-block btn-lg gradient-custom-4 text-body" name="login_account">Uitloggen</a>
                                    </div>
                                    <?php
                                    if (isset($message)) {
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