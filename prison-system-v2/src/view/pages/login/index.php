<?php
session_start();

use Controller\Functions;

$_SESSION['current_page'] = 'login';

include '../header.php';

if (isset($_POST['login']) && !empty($_POST["username"]) && !empty($_POST["password"])) {
    $login = (new Functions)->sign_in($_POST['username'] ?? "", $_POST['password'] ?? "");
    if (!empty($login['success'])) {
        header('Location: ../dashboard');
    }
}

?>
<main>
    <section class="vh-100" style="background-color: #508bfc;">
        <div class="container py-5 h-100">
            <div class="row d-flex justify-content-center align-items-center h-100">
                <div class="col-12 col-md-8 col-lg-6 col-xl-5">
                    <div class="card shadow-2-strong" style="border-radius: 1rem;">
                        <div class="card-body p-5">
                            <h3 class="mb-5 text-center">Log in - Prison System</h3>
                            <form method="POST" name="hello">
                                <div class="form-outline mb-4">
                                    <label for="username">Gebruikersnaam</label>
                                    <input type="name" class="form-control" id="username" name="username" placeholder="Geef hier je gebruikersnaam op">
                                </div>
                                <div class="form-outline mb-4">
                                    <label for="password">Wachtwoord</label>
                                    <input type="password" class="form-control" id="password" name="password" placeholder="Geef hier je wachtwoord op">
                                    <i class="bi bi-eye-slash" id="togglePassword"></i>
                                </div>
                                <?php
                                if (!empty($login["error"])) {
                                    echo '<div class="alert alert-danger" role="alert">' . $login["error"] . '</div>';
                                }
                                if (!empty($_SESSION["success_message"])) {
                                    echo '<div class="alert alert-success" role="alert">' . $_SESSION["success_message"] . '</div>';
                                    unset($_SESSION["success_message"]);
                                }
                                ?>
                                <button class="btn btn-primary btn-lg btn-block" type="submit" name="login">Login</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</main>
<?php require_once '../footer.php'; ?>