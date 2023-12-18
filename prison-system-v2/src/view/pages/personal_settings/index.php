<?php
session_start();

use Controller\Functions;

$_SESSION['current_page'] = 'persoonlijke instellingen';

include '../header.php';

if (isset($_POST['update_username']) && !empty($_POST["current_username"]) && !empty($_POST["new_username"])) {
    $update_username = (new Functions)->update_username($_POST['current_username'] ?? "", $_POST['new_username'] ?? "");
}

if (
    isset($_POST['update_password']) &&
    !empty($_POST["current_password"]) &&
    !empty($_POST["new_password1"]) &&
    !empty($_POST["new_password2"]) &&
    !empty($_POST['id'])
) {
    if ($_POST["new_password1"] !== $_POST["new_password2"]) {
        $update_password = ['error' => 'Wachtwoorden komen niet overeen'];
    } else {
        $update_password = (new Functions)->update_password($_POST['current_password'] ?? "", password_hash($_POST['new_password1'], PASSWORD_DEFAULT) ?? "", $_POST['id'] ?? "");
    }
}

if (isset($_POST['delete_account']) && !empty($_POST["delete_account"])) {
    $delete_account = (new Functions)->delete_account($_POST['delete_account']);
    if (!empty($delete_account["success"])) {
        if (array_key_exists('id', $_SESSION)) {
            unset($_SESSION['id']);
        }
        $_SESSION['success_message'] = $delete_account["success"];
        header('location: ../login');
    }
}

$account = (new Functions)->get_account_info($_SESSION['user']['id'] ?? "");

?>
<main>
    <h1>Je bevind je op de <?php echo $_SESSION['current_page'] ?> pagina</h1>
    <div class="container">
        <div class="row bg-light">
            <h2>Verander gebruikersnaam</h2>
            <div class="col-sm-4">
                <form method="POST">
                    <label for="current_username">Huidige gebruikersnaam</label>
                    <div class="form-group pass_show">
                        <input type="text" value="<?php echo $account['username'] ?>" id="current_username" name="current_username" class="form-control" placeholder="Huidige gebruikersnaam">
                    </div>
                    <label for="new_username">Nieuwe gebruikersnaam</label>
                    <div class="form-group pass_show">
                        <input type="text" value="" id="new_username" name="new_username" class="form-control" placeholder="Nieuwe gebruikersnaam">
                    </div>
                    <?php
                    if (!empty($update_username["error"])) {
                        echo '<div class="alert alert-danger" role="alert">' . $update_username["error"] . '</div>';
                    }
                    if (!empty($update_username["success"])) {
                        echo '<div class="alert alert-success" role="alert">' . $update_username["success"] . '</div>';
                    }
                    ?>
                    <button type="submit" class="btn btn-primary" name="update_username">Opslaan</button>
                </form>
            </div>
        </div>
        <div class="row bg-light">
            <h2>Verander wachtwoord</h2>
            <div class="col-sm-4">
                <form method="POST">
                    <label for="password1">Huidige wachtwoord</label>
                    <div class="form-group pass_show">
                        <input type="password" value="" id="password1" name="current_password" class="form-control" placeholder="Huidige wachtwoord">
                        <i class="bi bi-eye-slash" id="togglePassword1"></i>
                    </div>
                    <label for="password2">Nieuwe wachtwoord</label>
                    <div class="form-group pass_show">
                        <input type="password" value="" id="password2" name="new_password1" class="form-control" placeholder="Nieuwe wachtwoord">
                        <i class="bi bi-eye-slash" id="togglePassword2"></i>
                    </div>
                    <label for="password3">Herhaal nieuwe wachtwoord</label>
                    <div class="form-group pass_show">
                        <input type="password" value="" id="password3" name="new_password2" class="form-control" placeholder="Herhaal nieuwe wachtwoord">
                        <i class="bi bi-eye-slash" id="togglePassword3"></i>
                    </div>
                    <?php
                    if (!empty($update_password["error"])) {
                        echo '<div class="alert alert-danger" role="alert">' . $update_password["error"] . '</div>';
                    }
                    if (!empty($update_password["success"])) {
                        echo '<div class="alert alert-success" role="alert">' . $update_password["success"] . '</div>';
                    }
                    ?>
                    <input type="hidden" name="id" value="<?php echo $account['id_user'] ?>">
                    <button type="submit" class="btn btn-primary" name="update_password">Opslaan</button>
                </form>
            </div>
        </div>
        <div class="row bg-light">
            <h2>Overige</h2>
            <div class="col-sm-4">
                <label for="service_number">Dienstnummer</label>
                <div class="form-group pass_show">
                    <input type="text" value="<?php echo $account['id_service_number'] ?>" id="service_number" class="form-control" placeholder="" disabled>
                </div>
            </div>
            <div class="col-sm-4">
                <label for="service_number">Functie</label>
                <div class="form-group pass_show">
                    <input type="text" value="<?php echo $account['function'] ?>" class="form-control" placeholder="" disabled>
                </div>
            </div>
            <div class="col-sm-4">
                <label for="supervisor">Supervisor machtging</label>
                <div class="form-group pass_show">
                    <input type="text" value="<?php echo $account['supervisor'] == 1 ? "Ja" : "Nee" ?>" id="supervisor" class="form-control" placeholder="" disabled>
                </div>
            </div>
        </div>
        <div class="row bg-light">
            <div class="col-sm-4">
                <div class="form-group pass_show">
                    <form method="POST" class="delete-account">
                        <button type="submit" class="btn btn-danger" name="delete_account" value="<?php echo $account['id_user'] ?>">Verwijder definitief account</button>
                        <p>(Let op! Dit kan niet ongedaan worden gemaakt!)</p>
                        <?php
                        if (!empty($delete_account["error"])) {
                            echo '<div class="alert alert-danger" role="alert">' . $delete_account["error"] . '</div>';
                        }
                        if (!empty($delete_account["success"])) {
                            echo '<div class="alert alert-success" role="alert">' . $delete_account["success"] . '</div>';
                        }
                        ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</main>

<?php require_once '../footer.php'; ?>