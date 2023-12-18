<?php
session_start();

require_once $_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php";

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

use Controller\Functions;

$_SESSION['current_page'] = 'dashboard';

if (
    array_key_exists('user', $_SESSION) && array_key_exists('id', $_SESSION['user']) &&
    !empty($_SESSION['user']['id'])
) {
    $is_supervisor = (new Functions)->get_account_info($_SESSION['user']['id'])['supervisor'] == 1 ? true : false;
}

require_once '../header.php';

?>
<main>
    <h1>Je bevind je op de <?php echo $_SESSION['current_page'] ?> pagina</h1>
    <nav class="tiles">
        <ul>
            <li class="prisoners">
                <a href="../prisoner_management" class="card" style="width: 18rem;">
                    <div class="imgContainer">
                        <span class="overlay"></span>
                        <img class="card-img-top" src="../../img/gevangenen_beheren.jpeg" alt="Gevangenen beheren">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Gevangenenbeheer</h5>
                        <p class="card-text">Beheer hier de gegevens van de gevangenen</p>
                    </div>
                </a>
            </li>
            <li class="cells">
                <a href="../cell_management" class="card" style="width: 18rem;">
                    <div class="imgContainer">
                        <span class="overlay"></span>
                        <img class="card-img-top" style="width:160%" src="../../img/gevangenen.jpeg" alt="Gevangenen">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Celbeheer</h5>
                        <p class="card-text">Beheer hier de cellen</p>
                    </div>
                </a>
            </li>
            <li class="regist_prisoner">
                <a href="../add_prisoner" class="card" style="width: 18rem;">
                    <div class="imgContainer">
                        <span class="overlay"></span>
                        <img class="card-img-top" style="width:90%" src="../../img/aanmelden_prisoner.png" alt="Aanmelden gevangenen">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Gevangenen aanmelden</h5>
                        <p class="card-text">Meld hier nieuwe gevangenen aan</p>
                    </div>
                </a>
            </li>
            <?php
            $isSupervisor = $is_supervisor === true;
            ?>
            <li class="users <?php echo $isSupervisor ? '' : 'disabled' ?>">
                <?php if (!$isSupervisor) : ?>
                    <span class="overlay"></span>
                <?php endif; ?>
                <a href="<?php echo $isSupervisor ? '../user_management' : '' ?>" class="card" style="width: 18rem;">
                    <div class="imgContainer">
                        <?php if (!$isSupervisor) : ?>
                            <i class="bi bi-ban"></i>
                        <?php endif; ?>
                        <span class="overlay"></span>
                        <img class="card-img-top" src="../../img/gebruikersbeheer.jpeg" alt="Gebruikersbeheer">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">Gebruikersbeheer - Panel</h5>
                        <p class="card-text">Hier kan je de gebruikers van de admin paneel beheren</p>
                    </div>
                </a>
            </li>
        </ul>
    </nav>
</main>

<?php require_once '../footer.php'; ?>