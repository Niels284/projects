<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$_SESSION['current_page'] = 'edit';
require_once '../../../../controller/functions.php';
require_once '../../header.php';

use Controllers\Functions;

$func = new Functions();
$products = $func->get("products", "*", null);
$description = json_decode($products[$_GET['id'] - 1]->description, true);
?>
<main>
  <section class="pageContent">
    <h1 class="heading">Pakket <?php echo $_GET['id'] ?></h1>
    <form class="edit-form">
      <label for="pakketName" class="edit-form-label">Naam</label>
      <input name="pakketName" type="text" class="edit-form-input" value="<?php echo $products[$_GET['id'] - 1]->name ?>" required>
      <label for="pakketPrijs" class="edit-form-label">Prijs <span class="textGrey">(Euro)</span></label>
      <input name="pakketPrijs" type="text" class="edit-form-input" value="<?php echo $products[$_GET['id'] - 1]->price ?>" required>
      <label for="pakketWebruimte" class="edit-form-label">Webruimte <span class="textGrey">(GB)</span></label>
      <input name="pakketWebruimte" type="text" class="edit-form-input" value="<?php echo $description['Webruimte'] ?>" required>
      <label for="pakketDomeinnaam" class="edit-form-label">Domeinnaam <span class="textGrey">(aantal)</span></label>
      <input name="pakketDomeinnaam" type="text" class="edit-form-input" value="<?php echo $description['Domeinnaam'] ?>" required>
      <label for="pakketSSL" class="edit-form-label">Gratis SSL-certificaat <span class="textGrey">(aantal)</span></label>
      <input name="pakketSSL" type="text" class="edit-form-input" value="<?php echo $description['SSL-certificaat'] ?>" required>
      <div class="edit-form-radio-container">
        <div class="edit-form-radio-option">
          <input type="radio" name="hostType" value="A" class="edit-form-radio" <?php echo $description['Databases'] ?? '' ? 'checked' : '' ?> required>
          <div class="edit-form-radio-inner">
            <label id="dbInput" for="pakketDatabases" class="edit-form-label">Databases <span class="textGrey">(aantal)</span></label>
            <input name="pakketDatabases" type="text" class="edit-form-input" value="<?php echo $description['Databases'] ?? "" ?>">
            <label for="pakketMail" class="edit-form-label">Mailadressen mogelijk <span class="textGrey">(aantal)</span></label>
            <input name="pakketMail" type="text" class="edit-form-input" value="<?php echo $description['Mailadressen'] ?? "" ?>">
          </div>
        </div>
        <div class="edit-form-radio-option">
          <input id="radio1" type="radio" name="hostType" value="B" class="edit-form-radio" <?php echo $description['Processoren'] ?? '' ? 'checked' : '' ?> required>
          <div class="edit-form-radio-inner">
            <label for="pakketProcessors" class="edit-form-label">Processoren <span class="textGrey">(aantal)</span></label>
            <input name="pakketProcessors" type="text" class="edit-form-input" value="<?php echo $description['Processoren'] ?? "" ?>">
            <label for="pakketMemory" class="edit-form-label">Geheugen <span class="textGrey">(GB)</span></label>
            <input name="pakketMemory" type="text" class="edit-form-input" value="<?php echo $description['Geheugen'] ?? "" ?>">
          </div>
        </div>
      </div>
      <div class="edit-form-button-container">
        <a href="../../admin" class="edit-form-cancel">Cancel</a>
        <button type="submit" class="edit-form-submit">Save</button>
      </div>
    </form>
  </section>
</main>
<?php require_once '../../footer.php'; ?>
