<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

$_SESSION['current_page'] = 'admin';
require_once '../../../../controller/functions.php';
require_once '../../header.php';


use Controllers\Functions;

$func = new Functions();
$news_messages = $func->get("news_messages", "*", null);
?>
<main>
    <div class="admin-nav">
        <div class="admin-nav-inner">
            <a href="../?admin_page=pakketten" class="admin-nav-link">Pakketten</a>
            <a href="../?admin_page=gebruikers" class="admin-nav-link">Gebruikers</a>
            <a href="../?admin_page=bestellingen" class="admin-nav-link">Bestellingen</a>
            <a href="../?admin_page=nieuwsberichten" class="admin-nav-link nav-active">Nieuwsberichten</a>
        </div>
    </div>
    <section class="pageContent">
        <div class="admin-content">
            <table class="admin-content-table">
                <!-- vul de tabel met correcte data via php -->
                <tr class="table-row table-header-row">
                    <th>ID</th>
                    <th>Titel</th>
                    <th>Datum</th>
                    <th>Acties</th>
                </tr>

                <?php
                for ($i = 0; $i < count($news_messages); $i++) {
                    echo "<tr class=\"table-row table-data-row\">";
                    echo "<td class=\"table-data-messageid\">" . $news_messages[$i]->messageid . "</td>";
                    echo "<td class=\"table-data-title\">" . $news_messages[$i]->title . "</td>";
                    echo "<td class=\"table-data-date\">" . $news_messages[$i]->date . "</td>";
                    echo "<td class=\"table-data-actions\">
                        <a href=\"../edit?messageid=" . $news_messages[$i]->messageid . "\" class=\"table-data-actions-edit\">edit</a>
                        <a href=\"?\" class=\"table-data-actions-delete\">delete</a>
                    </td>";
                    echo "</tr>";
                }

                ?>
            </table>
            <a href="./edit" class="link-button">nieuw</a>
        </div>
    </section>
</main>
<?php require_once '../../footer.php'; ?>