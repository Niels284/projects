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
$orders = $func->get(
    "order_details",
    "order_details.orderid AS order_id,
    products.name AS product_name,
    order_details.quantity AS quantity,
    customers.name AS customer_name",
    "INNER JOIN customers ON customers.customerid = order_details.customerid 
    INNER JOIN products ON products.productid = order_details.productid"
);

?>
<main>
    <div class="admin-nav">
        <div class="admin-nav-inner">
            <a href="../?admin_page=pakketten" class="admin-nav-link">Pakketten</a>
            <a href="../?admin_page=gebruikers" class="admin-nav-link">Gebruikers</a>
            <a href="../?admin_page=bestellingen" class="admin-nav-link nav-active">Bestellingen</a>
            <a href="../?admin_page=nieuwsberichten" class="admin-nav-link">Nieuwsberichten</a>
        </div>
    </div>
    <section class="pageContent">
        <div class="admin-content">
            <table class="admin-content-table">
                <!-- vul de tabel met correcte data via php -->
                <tr class="table-row table-header-row">
                    <th>ID</th>
                    <th>Productnaam</th>
                    <th>Hoeveelheid</th>
                    <th>Klant</th>
                    <th>Acties</th>
                </tr>

                <?php
                for ($i = 0; $i < count($orders); $i++) {
                    echo "<tr class=\"table-row table-data-row\">";
                    echo "<td class=\"table-data-orderid\">" . $orders[$i]->order_id . "</td>";
                    echo "<td class=\"table-data-productname\">" . $orders[$i]->product_name . "</td>";
                    echo "<td class=\"table-data-quantity\">" . $orders[$i]->quantity . "</td>";
                    echo "<td class=\"table-data-customername\">" . $orders[$i]->customer_name . "</td>";
                    echo "<td class=\"table-data-actions\">
                        <a href=\"../edit?orderid=" . $orders[$i]->order_id . "\" class=\"table-data-actions-edit\">edit</a>
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