<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$_SESSION['current_page'] = 'pakket';
require_once '../../../controller/functions.php';
require_once '../header.php';

use Controllers\Functions;

$func = new Functions();

$product = ($func->get("products", "*", null)[$_GET['package'] - 1]);

if (isset($_GET['call']) && $_GET['call'] === 'sign_out') {
  return;
} elseif (!isset($_GET['package']) || $product == null) {
  $_SESSION['error_code'] = '404';
  $_SESSION['error_message'] = 'Deze pagina bestaat niet';
  header('location: ../error');
}

?>

<main>
  <section class="pageContent">
    <div class="description">
      <h1 class="heading"><?php echo $product->name ?></h1>
      <p class="textGrey">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur. Excepteur sint occaecat cupidatat non proident, sunt in culpa qui officia deserunt mollit anim id est laborum.</p>
    </div>
    <div class="details">
      <ul class="detailList">
        <?php
        foreach (json_decode($product->description) as $key => $value) {
          if ($key !== 'Price') {
            if ($value !== 0 && $value !== "0" && $value !== "" && $value !== null && $value !== "0 mogelijk") {
              echo '
              <li class="detailItem">
                <span class="detailKey">' . $key . '</span>
                <span class="detailValue textGrey">' . $value . '</span>
              </li>
            ';
            }
          }
        }
        echo
        '
          <li class="detailItem">
            <span class="detailKey">Prijs</span>
            <span class="detailValue textGrey">€' . number_format($product->price, 2, ',', ' ') . '</span>
          </li>
        ';
        ?>
      </ul>
    </div>
    <button class="orderButton">Bestel</button>
  </section>
</main>

<?php require_once '../footer.php'; ?>