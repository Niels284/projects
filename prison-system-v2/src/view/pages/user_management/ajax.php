<?php

require_once $_SERVER["DOCUMENT_ROOT"] . "/vendor/autoload.php";

use Controller\Functions;

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

header('Access-Control-Allow-Origin: *');
header('Content-Type: application/json');

$id_user = $_POST['id_user'];
$user = (new Functions)->get_account_info($id_user);
echo json_encode($user);
