<?php

use Controllers\Calculations;

ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
$path = Calculations::getPath(__DIR__, 'view');
?>
</body>
<?php
// add js files based on filenames
switch ($_SESSION['current_page']) {
    case 'edit':
        echo '<script type="module" src="../../../script/pakketvalidation.js"></script>';
        break;
    default:
        break;
}
ob_end_flush(); // Verzend de gebufferde uitvoer naar de browser
?>

</html>