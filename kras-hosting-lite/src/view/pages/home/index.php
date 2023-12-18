<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$_SESSION['current_page'] = 'home';
require_once '../../../controller/functions.php';
require_once '../header.php';

use Controllers\Functions;
use Controllers\Calculations;

$func = new Functions();
$calc = new Calculations();

$products = $func->get("products", "*", null);
$news_messages = $func->get("news_messages", "*", null);

?>
<main>
    <section class="bigHeader">
        <div class="bigHeaderInner">
            <div class="bigTagline">HOSTING SERVICES</div>
            <div class="bigTitle">KRAS <span class="redText">HOSTING</span></div>
        </div>
    </section>
    <section class="pageContent">
        <div class="pakketContainer">
            <div class="pakketHeader">
                <h1 class="heading">Pakketen</h1>
                <p class="textGrey">Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut labore et dolore.</p>
            </div>
            <div class="pakketList">
                <?php
                for ($i = 0; $i < count($products); $i++) {
                    $prodId = $products[$i]->productid;
                    echo '<a class="pakketItem" href="../pakket?package=' . $prodId . '">';
                    echo '<h2 class="pakketTitle">Pakket: ' . $prodId . '</h2>';
                    echo '<p class="pakketName">' . $products[$i]->name . '</p>';
                    echo '</a>';
                }
                ?>
            </div>
        </div>
        <div class="newsContainer">
            <?php
            $currentDate = $calc->getCurrentTime('date');
            $today_news = [];
            $old_news = [];

            function customSort($a, $b)
            {
                return strtotime($b->date) - strtotime($a->date);
            }

            function displayNews($news, $title)
            {
                echo "<h1 class='heading'>$title</h1>";
                echo "<div class='newsToday'>";
                echo "<ul>";

                foreach ($news as $news_message) {
                    echo "<li class='textGrey'>";
                    echo "<h1 class='heading'>$news_message->title</h1>";
                    echo "<p>$news_message->message</p>";
                    echo "<p class='textGrey'>$news_message->date</p>";
                    echo "</li>";
                }

                echo "</ul>";
                echo "</div>";
            }

            foreach ($news_messages as $news_message) {
                $messageDate = date('d-m-Y', strtotime($news_message->date));

                if ($messageDate === $currentDate) {
                    $today_news[] = $news_message;
                } else {
                    $old_news[] = $news_message;
                }
            }

            usort($today_news, 'customSort');
            usort($old_news, 'customSort');

            displayNews($today_news, 'Vandaag');
            displayNews($old_news, 'Gisteren en eerder');
            ?>
        </div>
    </section>
</main>
<?php require_once '../footer.php'; ?>