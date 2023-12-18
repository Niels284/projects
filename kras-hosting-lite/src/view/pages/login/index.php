<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Controllers\Functions;

session_start();

$_SESSION['current_page'] = 'login';
require_once '../../../controller/functions.php';
require_once '../header.php';

if (isset($_POST['sign_in'])) {
  $login = (new Functions)->sign_in($_POST["username"], $_POST["password"]);
  $_POST = array();
  if (!empty($login['succes'])) {
    header('location: ../admin');
  }
}
if (isset($_SESSION['user']) && array_key_exists('id', $_SESSION['user'])) {
  header('location: ../admin');
}
?>


<main>
  <section class="pageContent">
    <div class="login-container">
      <h1 class="heading">Log in</h1>
      <form class="login-form" method="POST">
        <div class="login-form-input-container">
          <label for="email" class="login-form-label">Username</label>
          <input name="username" type="text" class="login-form-input" placeholder="Username"></input>
        </div>
        <div class="login-form-input-container">
          <label for="password" class="login-form-label">Password</label>
          <input name="password" type="password" class="login-form-input" placeholder="Password"></input>
        </div>
        <div class="login-form-check-container">
          <label class="login-form-label">
            <input name="remember" type="checkbox" class="login-form-input-check">
            Remember me (coming soon)
          </label>
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
        <button class="login-form-button" name="sign_in">Log in</button>
        <a href="#" class="login-link-register">Register (coming soon)</a>
      </form>
    </div>
  </section>
</main>
<?php require_once '../footer.php'; ?>