<?php
session_start();

require_once $_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php";
include_once "../../../controller/functions.php";

use Controller\Database;

error_reporting(E_ALL);
ini_set('display_errors', '1');
ini_set('display_startup_errors', 1);

$database = new Database();

// password om te testen = Testtest!1
$codeError;
$unhashedCode = $_SESSION['unhashedCode']; // omdat de php mailer niet wil werken, even op deze manier gedaan
if (isset($_SESSION['message'])) {
    $message = $_SESSION['message'];
    unset($_SESSION['message']);
} else {
    $message;
}

if (!isset($_SESSION['last_access'])) {
    $_SESSION['last_access'] = time();
} else if ((time() - $_SESSION['last_access']) > 60) {
    $codeError = ['error' => "De code is verlopen!"];
    unset($_SESSION['last_access']);
    unset($_SESSION['user']);
    unset($_SESSION['code']);
    header('Location:../register');
}

if (
    isset($_POST['code']) && isset($_POST['randcheck']) &&
    $_POST['randcheck'] == $_SESSION['rand']
) {
    $code = is_valid_code(trim($_POST['code']));
    if (!isset($code['error']) && password_verify($code, $_SESSION['code'])) {
        if (
            isset($_SESSION['user']) && isset($_SESSION['user']['firstname']) && isset($_SESSION['user']['lastname']) &&
            isset($_SESSION['user']['emailaddress']) && isset($_SESSION['user']['username']) && isset($_SESSION['user']['password'])
        ) {
            $firstname = $_SESSION['user']['firstname'];
            $lastname = $_SESSION['user']['lastname'];
            $emailaddress = $_SESSION['user']['emailaddress'];
            $username = $_SESSION['user']['username'];
            $password1 = $_SESSION['user']['password'];
            $_POST = array();

            if (!isset($message) && !isset($codeError)) {
                $salt = bin2hex(random_bytes(64));
                $hashedPassword = password_hash($password1 . $salt, PASSWORD_BCRYPT);
                $responseMessage = $database->createUser(
                    $firstname,
                    $lastname,
                    $emailaddress,
                    $username,
                    $salt,
                    $hashedPassword
                );
                if (isset($responseMessage['success'])) {
                    $_SESSION['message'] = $responseMessage['success'];
                    unset($_SESSION['last_access']);
                    unset($_SESSION['user']);
                    unset($_SESSION['code']);
                    header('Location:../login');
                } else if (isset($responseMessage['error'])) {
                    $message = $responseMessage;
                }
            }
        } else {
            unset($_SESSION['last_access']);
            unset($_SESSION['user']);
            unset($_SESSION['code']);
            header('Location:../register');
        }
    } else {
        $codeError = ['error' => "De code is onjuist, probeer het nog eens!"];
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
    <title>Hasing - Salting - Authentication</title>
</head>

<body>
    <section class="vh-100 bg-image" style="background-image: url('https://mdbcdn.b-cdn.net/img/Photos/new-templates/search-box/img4.webp');">
        <div class="mask d-flex align-items-center h-100 gradient-custom-3">
            <div class="container h-100">
                <div class="row d-flex justify-content-center align-items-center h-100">
                    <div class="col-12 col-md-9 col-lg-7 col-xl-6">
                        <div class="card" style="border-radius: 15px;">
                            <div class="card-body p-5">
                                <h2 class="text-uppercase text-center mb-5">Code controleren</h2>
                                <form method="POST">
                                    <div class="form-outline mb-4">
                                        <input type="text" id="code" name="code" class="form-control form-control-lg" placeholder="000000" required />
                                        <label class="form-label" for="code">Vul hier een 6-cijferige code in die je via je mail heb ontvangen</label>
                                    </div>
                                    <?php
                                    if (isset($codeError) && !empty($codeError)) {
                                        echo "<div class='alert alert-danger' role='alert'>" . $codeError['error'] . "</div>";
                                        $codeError = "";
                                    }
                                    ?>
                                    <div class=" d-flex justify-content-center" style="margin-bottom:20px">
                                        <input type="hidden" name="randcheck" value="<?php echo $rand; ?>" />
                                        <button type="submit" class="btn btn-success btn-block btn-lg gradient-custom-4 text-body" name="create_account">Controleren</button>
                                    </div>
                                    <?php
                                    if (isset($message) && !empty($message)) {
                                        if (!empty($message['error'])) {
                                            echo "<div class='alert alert-danger' role='alert'>" . $message['error'] . "</div>";
                                        } else if (!empty($message['success'])) {
                                            echo "<div class='alert alert-success' role='alert'>" . $message['success'] . "!</div>";
                                        } else {
                                            echo "<div class='alert alert-success' role='alert'>$message</div>";
                                        }
                                        $message = "";
                                    }
                                    ?>
                                    <a href="../login">Ik heb al een account</a>
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