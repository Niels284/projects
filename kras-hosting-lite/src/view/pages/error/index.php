<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$_SESSION['current_page'] = 'error';
require_once '../../../controller/functions.php';
require_once '../header.php';
?>

<main>
    <section class="pageContent">
        <div class="pakketContainer">
            <div class="pakketHeader">
                <h1 class="heading"><?php echo 'Error ' . $_SESSION['error_code'] ?></h1>
                <p class="textGrey"><?php echo $_SESSION['error_message'] ?></p>
                <a href="../home">
                    <button class="orderButton" style="margin-top:5px;">Terug naar home</button>
                </a>
            </div>
        </div>
    </section>
</main>

<?php require_once '../footer.php'; ?>